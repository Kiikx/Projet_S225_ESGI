# Lancer tous les services
up:
	docker-compose up -d --build

# Arrêter les services
down:
	docker-compose down

# Accès au conteneur app
bash:
	docker exec -it laravel-app bash

# Installation des dépendances Composer
install:
	docker exec laravel-app composer install

# Installation des dépendances NPM (si frontend présent)
npm-install:
	docker exec laravel-app npm install

# Lancer les migrations Laravel
migrate:
	docker exec laravel-app php artisan migrate

# Réinitialiser la base de données
fresh:
	docker exec laravel-app php artisan migrate:fresh --seed

# Lancer Laravel (optionnel si géré par Nginx)
serve:
	docker exec -it laravel-app php artisan serve --host=0.0.0.0 --port=8000

# Donner les permissions nécessaires
permissions:
	sudo chmod -R 777 storage bootstrap/cache

# Nettoyer les caches Laravel
clear:
	docker exec laravel-app php artisan config:clear && \
	docker exec laravel-app php artisan route:clear && \
	docker exec laravel-app php artisan view:clear
