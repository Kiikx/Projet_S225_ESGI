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
    export DB_HOST="${MYSQL_HOST:-127.0.0.1}"
    export DB_PORT="${MYSQL_PORT:-3306}"
    export DB_USERNAME="${MYSQL_USER:-laravel}"
    export DB_PASSWORD="${MYSQL_PASSWORD:-laravel}"
    export DB_DATABASE="${MYSQL_DATABASE:-kanboard}"
    echo "[INFO] DB_HOST=$DB_HOST, DB_PORT=$DB_PORT, DB_USERNAME=$DB_USERNAME, DB_DATABASE=$DB_DATABASE"
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
envfile=".env"
if [ ! -f "$envfile" ]; then
    cp .env.example .env
fi
php artisan key:generate --force

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
