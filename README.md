# AppWoyofall - Application de Gestion d'Électricité

Application PHP pour la gestion des achats d'électricité avec système de compteurs et tranches tarifaires.

## 🚀 Déploiement sur Render

### Prérequis

- Compte Render
- Base de données PostgreSQL (Railway ou Render)

### Étapes de déploiement

1. **Fork ou clonez ce repository**

2. **Connectez votre repository à Render**

   - Allez sur [render.com](https://render.com)
   - Cliquez sur "New +" → "Web Service"
   - Connectez votre repository GitHub/GitLab

3. **Configurez les variables d'environnement**
   Dans Render, ajoutez ces variables :

   ```
   DB_HOST=votre_host_postgresql
   DB_PORT=5432
   DB_NAME=votre_nom_base
   DB_USER=votre_utilisateur
   DB_PASSWORD=votre_mot_de_passe
   ```

4. **Déployez**
   - Render détectera automatiquement le `Dockerfile`
   - Le build se fera automatiquement
   - L'application sera accessible sur l'URL fournie par Render

### Configuration de la base de données

1. **Créez une base PostgreSQL** (Railway recommandé)
2. **Récupérez les informations de connexion**
3. **Ajoutez-les dans les variables d'environnement Render**

## 🛠️ Développement local

### Avec Docker Compose

```bash
# Copier le fichier d'environnement
cp env.example .env

# Éditer les variables de base de données
nano .env

# Démarrer les services
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install

# Configurer la base de données
docker-compose exec app php setup_database.php
```

### Sans Docker

```bash
# Installer les dépendances
composer install

# Configurer l'environnement
cp env.example .env
# Éditer .env avec vos paramètres de base de données

# Configurer la base de données
php setup_database.php

# Démarrer le serveur
php -S localhost:8000
```

## 📊 API Endpoints

### Clients

- `GET /api/client` - Récupérer tous les clients
- `POST /api/client` - Créer un nouveau client
- `GET /api/client/{id}` - Récupérer un client par ID

### Compteurs

- `GET /api/compteur` - Récupérer tous les compteurs
- `POST /api/compteur` - Créer un nouveau compteur
- `GET /api/compteur/{numero}` - Récupérer un compteur par numéro
- `PUT /api/compteur/{numero}/consommation` - Mettre à jour la consommation

### Tranches

- `GET /api/tranche` - Récupérer toutes les tranches
- `POST /api/tranche` - Créer une nouvelle tranche

### Achats

- `GET /api/achat` - Récupérer tous les achats
- `POST /api/achat` - Effectuer un achat d'électricité

## 🏗️ Architecture

- **Entities** : Modèles de données (Client, Compteur, Tranche, Achat)
- **Repositories** : Accès aux données
- **Services** : Logique métier
- **Controllers** : Gestion des requêtes HTTP
- **Interfaces** : Contrats pour l'injection de dépendances

## 📈 Fonctionnalités

- Gestion des clients avec informations complètes
- Système de compteurs avec consommation mensuelle/annuelle
- Tranches tarifaires selon les tarifs Sénénelec
- Calcul automatique du statut de tranche
- Mise à jour automatique de la consommation après achat
- API REST complète

## 🔧 Technologies

- **Backend** : PHP 8.1, PDO, PostgreSQL
- **Serveur** : Nginx + PHP-FPM
- **Containerisation** : Docker
- **Déploiement** : Render
- **Base de données** : PostgreSQL (Railway)

## 📝 Tests

Utilisez les fichiers dans le dossier `test/` :

- `.http` files pour VS Code REST Client
- `test_api.php` pour les tests automatisés
- `curl` pour les tests manuels

## 🚨 Variables d'environnement

Créez un fichier `.env` basé sur `env.example` :

```
DB_HOST=votre_host
DB_PORT=5432
DB_NAME=votre_base
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```
