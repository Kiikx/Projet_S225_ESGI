#!/bin/bash
set -e

# DEBUG: Voir ce qui est disponible dans le container
echo "[DEBUG] Contenu de /var/www:"
ls -la /var/www/
echo "[DEBUG] Variables d'environnement Railway:"
env | grep -E "(RAILWAY|PORT)" || echo "Aucune variable Railway trouvée"
echo "[DEBUG] Variables MySQL Railway:"
env | grep -E "MYSQL_" || echo "Aucune variable MySQL trouvée"

# Déterminer le bon working directory selon l'environnement
if [ "$RAILWAY_ENVIRONMENT" = "production" ] || [ -n "$PORT" ]; then
    # Vérifier si /var/www/src existe
    if [ -d "/var/www/src" ]; then
        cd /var/www/src
        echo "[INFO] Railway détecté - working directory: /var/www/src"
    else
        echo "[WARNING] /var/www/src n'existe pas, reste dans /var/www"
        cd /var/www
    fi
else
    # En dev local, le volume mount met Laravel directement dans /var/www/
    cd /var/www
    echo "[INFO] Dev local détecté - working directory: /var/www"
fi

# Mapper les variables Railway vers Laravel (sur Railway seulement)
if [ "$RAILWAY_ENVIRONMENT" = "production" ] || [ -n "$PORT" ]; then
    echo "[INFO] Configuration Railway MySQL..."
    
    if [ -n "$MYSQL_URL" ]; then
        echo "[INFO] Parsing MYSQL_URL: $MYSQL_URL"
        
        # Parser l'URL MySQL: mysql://user:pass@host:port/db
        # Extraire les composants
        DB_USERNAME=$(echo "$MYSQL_URL" | sed -n 's|mysql://\([^:]*\):.*|\1|p')
        DB_PASSWORD=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^:]*:\([^@]*\)@.*|\1|p')
        DB_HOST=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^@]*@\([^:]*\):.*|\1|p')
        DB_PORT=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^@]*@[^:]*:\([0-9]*\)/.*|\1|p')
        DB_DATABASE=$(echo "$MYSQL_URL" | sed -n 's|mysql://[^/]*/\(.*\)|\1|p')
        
        export DB_HOST DB_PORT DB_USERNAME DB_PASSWORD DB_DATABASE
        
        echo "[INFO] Variables Laravel from MYSQL_URL:"
        echo "DB_HOST=$DB_HOST"
        echo "DB_PORT=$DB_PORT" 
        echo "DB_USERNAME=$DB_USERNAME"
        echo "DB_DATABASE=$DB_DATABASE"
    else
        echo "[WARNING] MYSQL_URL not found, using defaults"
        export DB_HOST="127.0.0.1"
        export DB_PORT="3306"
        export DB_USERNAME="laravel"
        export DB_PASSWORD="laravel"
        export DB_DATABASE="kanboard"
    fi
fi

# Attendre que MySQL soit disponible (seulement en dev, Railway gère ça)
if [ "$RAILWAY_ENVIRONMENT" != "production" ] && [ -z "$PORT" ]; then
    echo "[INFO] Attente de MySQL..."
    while ! php artisan migrate:status >/dev/null 2>&1; do
        echo "[INFO] MySQL pas encore disponible, attente 3s..."
        sleep 3
    done
    echo "[INFO] MySQL est prêt !"
else
    echo "[INFO] Railway environnement détecté - skip du test MySQL"
fi

# Installer les dépendances PHP si vendor n'existe pas
if [ ! -d "vendor" ]; then
    composer install
fi

# Installer les dépendances JS à chaque démarrage (plus sûr)
npm install

# Générer la clé Laravel si elle n'existe pas
if [ "$RAILWAY_ENVIRONMENT" = "production" ] || [ -n "$PORT" ]; then
    # Sur Railway, créer un .env minimal avec configs essentielles
    echo "[INFO] Railway - création .env minimal avec configs essentielles"
    cat > .env << EOF
APP_KEY=
APP_ENV=production
APP_DEBUG=true
APP_URL=https://$RAILWAY_PUBLIC_DOMAIN

LOG_CHANNEL=stderr
LOG_LEVEL=debug

# Force HTTPS
FORCE_HTTPS=true
ASSET_URL=https://$RAILWAY_PUBLIC_DOMAIN

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD
EOF
    
    echo "[DEBUG] Contenu du .env créé:"
    cat .env
else
    # En dev local, créer le .env complet s'il n'existe pas
    envfile=".env"
    if [ ! -f "$envfile" ]; then
        cp .env.example .env
        echo "[INFO] Fichier .env créé depuis .env.example"
    fi
fi

php artisan key:generate --force

# Forcer HTTPS pour les assets et URLs sur Railway
if [ "$RAILWAY_ENVIRONMENT" = "production" ] || [ -n "$PORT" ]; then
    echo "[INFO] Configuration HTTPS pour Railway..."
    php artisan config:cache
fi

# Lancer les migrations automatiquement
php artisan migrate --force

# Donner les bons droits
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Compiler les assets avec Vite (et afficher une erreur claire si échec)
echo "[INFO] Build front avec Vite..."
if ! npx vite build; then
    echo "[ERREUR] Le build Vite a échoué. Vérifie tes dépendances front."
    exit 1
fi

echo "[DEBUG] About to start PHP-FPM..."
echo "[DEBUG] PHP version: $(php --version | head -n1)"
echo "[DEBUG] Current directory: $(pwd)"
echo "[DEBUG] Available files: $(ls -la | head -5)"

# Détecter l'environnement et démarrer le bon serveur
if [ "$RAILWAY_ENVIRONMENT" = "production" ] || [ -n "$PORT" ]; then
    echo "[INFO] Starting Laravel development server for Railway..."
    exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
else
    echo "[INFO] Starting PHP-FPM server for development..."
    exec php-fpm -F
fi
