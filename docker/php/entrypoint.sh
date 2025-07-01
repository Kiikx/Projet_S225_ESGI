#!/bin/bash
set -e

cd /var/www

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

exec php-fpm 