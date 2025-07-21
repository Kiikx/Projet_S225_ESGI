# 📋 Kanboard - Gestion de Projets Kanban

Une application de gestion de projets moderne avec interface Kanban, développée avec Laravel et Vue.js.

## 🚀 Lancement Ultra-Rapide

**Pré-requis :** Docker installé sur votre machine

```bash
# 1. Cloner le projet
git clone [URL_DU_REPO]
cd Projet_S225_ESGI

# 2. Lancer l'application (tout automatique !)
docker-compose up -d

# 3. Attendre ~2 minutes le premier lancement
# L'application installe automatiquement :
# - Dépendances PHP (Composer)
# - Dépendances JS (npm)
# - Base de données (migrations)
# - Assets front-end (Vite build)

# 4. Accéder à l'application
# http://localhost:8080
```

**C'est tout ! 🎉** L'application est opérationnelle.

## 📱 Accès

- **Application Web** : http://localhost:8080
- **Base de données** : MySQL sur port 3307 (externe)
- **Logs** : `docker-compose logs -f app`

## 🛠️ Configuration Avancée

### Variables d'environnement (.env)

Le fichier `src/.env` est automatiquement configuré, mais vous pouvez personnaliser :

```env
# Base de données (Docker)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kanboard
DB_USERNAME=laravel
DB_PASSWORD=laravel

# Email (Mailtrap recommandé pour dev)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Commandes Utiles

```bash
# Voir les conteneurs
docker-compose ps

# Logs en temps réel
docker-compose logs -f app

# Arrêter l'application
docker-compose down

# Reconstruction complète
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Shell dans le conteneur PHP
docker-compose exec app bash
```

## 🏗️ Architecture Technique

- **Backend** : Laravel 11 + PHP 8.2-FPM
- **Frontend** : Vue.js 3 + Vite + Tailwind CSS
- **Base de données** : MySQL 8.0
- **Serveur web** : Nginx (proxy vers PHP-FPM)
- **Container** : Docker Compose

## ✨ Fonctionnalités

### Core Features
- ✅ Authentification (inscription/connexion/reset password)
- ✅ Gestion multi-projets
- ✅ Vue Kanban avec drag & drop
- ✅ Vue liste avec recherche/filtres
- ✅ Vue calendrier (jour/semaine/mois)
- ✅ Gestion des équipes (invitation par email)
- ✅ Tâches avec priorités, catégories, deadlines
- ✅ Interface responsive (mobile-friendly)
- ✅ Mode sombre/clair automatique

### Bonus Features
- 🔄 Temps réel (notifications live)
- 📊 Statistiques et rapports de projet
- 📱 Mode hors-ligne (PWA)
- 📅 Export iCal pour calendriers externes

## 🐛 Résolution de Problèmes

### Le conteneur ne démarre pas
```bash
# Vérifier les logs
docker-compose logs app

# Nettoyer et relancer
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Erreur de permissions
```bash
# Corriger les permissions (Linux/Mac)
sudo chown -R $USER:$USER src/
```

### Migration échoue
```bash
# Reset de la base de données
docker-compose down
docker volume rm projet_s225_esgi_dbdata
docker-compose up -d
```

## 👥 Équipe de Développement

Projet réalisé dans le cadre du cursus ESGI - Bachelor Développement.

---

**🎯 Application déployée et opérationnelle en moins de 3 minutes !**
