#!/bin/bash
set -e

cd /var/www

# Attendre que MySQL soit disponible
echo "[INFO] Attente de MySQL..."
while ! php artisan migrate:status >/dev/null 2>&1; do
    echo "[INFO] MySQL pas encore disponible, attente 3s..."
    sleep 3
done
echo "[INFO] MySQL est prêt !"

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

# Supprimer le fichier hot s'il existe (mode dev)
if [ -f "public/hot" ]; then
    echo "[INFO] Suppression du fichier hot (mode dev détecté)"
    rm -f public/hot
fi

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

echo "[INFO] Starting PHP-FPM server..."
exec php-fpm -F
