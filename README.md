# AppWoyofall - Application de Gestion d'Électricité

Application PHP moderne pour la gestion des achats d'électricité avec système de compteurs et tranches tarifaires.

## 🏗️ Architecture

### Structure du Projet

```
woyofall/
├── app/
│   ├── config/
│   │   ├── bootstrap.php      # Configuration initiale
│   │   ├── dependencies.yaml  # Injection de dépendances
│   │   ├── env.php           # Variables d'environnement
│   │   ├── helpers.php       # Fonctions utilitaires
│   │   └── routes.php        # Configuration des routes
│   └── core/
│       ├── App.php           # Classe principale
│       ├── Container.php     # Conteneur DI
│       ├── Router.php        # Routeur moderne
│       ├── Database.php      # Connexion BDD
│       └── Middlewares.php   # Middlewares
├── src/
│   ├── controller/           # Contrôleurs
│   ├── entity/              # Entités
│   ├── repository/           # Repositories
│   ├── service/             # Services
│   └── enum/                # Énumérations
├── migrations/              # Migrations BDD
├── public/                  # Point d'entrée web
├── routes/                  # Définition des routes
└── test/                   # Tests API
```

### Technologies

- **Backend** : PHP 8.1+ avec POO
- **Architecture** : MVC avec Injection de Dépendances
- **Base de données** : PostgreSQL
- **Serveur** : Nginx + PHP-FPM
- **Containerisation** : Docker
- **Déploiement** : Render
- **Gestion des dépendances** : Composer + YAML

## 🚀 Installation et Configuration

### Prérequis

- PHP 8.1+
- Composer
- PostgreSQL
- Docker (optionnel)

### Installation locale

```bash
# Cloner le projet
git clone <repository>
cd woyofall

# Installer les dépendances
composer install

# Configurer l'environnement
cp env.example .env
# Éditer .env avec vos paramètres

# Créer la base de données
php migrations/migration.php

# Remplir avec les données initiales
php migrations/seeder.php

# Démarrer le serveur
php -S localhost:8000 -t public
```

### Avec Docker

```bash
# Construire et démarrer
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install

# Configurer la base de données
docker-compose exec app php migrations/migration.php
docker-compose exec app php migrations/seeder.php
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
- `PUT /api/compteur/{numero}` - Mettre à jour un compteur
- `DELETE /api/compteur/{numero}` - Supprimer un compteur
- `GET /api/compteur/client/{clientId}` - Compteurs d'un client
- `PUT /api/compteur/{numero}/consommation` - Mettre à jour la consommation
- `POST /api/compteur/{numero}/reset` - Réinitialiser la consommation

### Tranches

- `GET /api/tranche` - Récupérer toutes les tranches
- `POST /api/tranche` - Créer une nouvelle tranche

### Achats

- `GET /api/achat` - Récupérer tous les achats
- `POST /api/achat` - Effectuer un achat d'électricité

## 🗄️ Base de Données

### Tables principales

- **client** : Informations des clients (nom, prénom, téléphone, CNI, adresse, civilité)
- **compteur** : Compteurs électriques avec consommation et statut
- **tranche** : Tranches tarifaires (Tranche 1-4 avec prix par kWh)
- **achat** : Historique des achats d'électricité

### Migrations

```bash
# Créer les tables
php migrations/migration.php

# Remplir avec les données initiales
php migrations/seeder.php
```

Le seeder récupère automatiquement les clients depuis l'API externe et insère les tranches, compteurs et achats de test.

## 🔧 Configuration

### Variables d'environnement (.env)

```env
# Base de données
DB_HOST=localhost
DB_PORT=5432
DB_NAME=woyofall
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe

# Application
URL=http://localhost:8000

# Cloudinary (optionnel)
CLOUD_NAME=votre_cloud_name
API_KEY=votre_api_key
API_SECRET=votre_api_secret
```

### Injection de Dépendances

Le fichier `app/config/dependencies.yaml` définit toutes les dépendances :

```yaml
dependencies:
  controllers:
    AchatController: App\Controller\AchatController
    ClientController: App\Controller\ClientController
    CompteurController: App\Controller\CompteurController
    TrancheController: App\Controller\TrancheController

  services:
    AchatService: App\Service\AchatService
    ClientService: App\Service\ClientService
    CompteurService: App\Service\CompteurService
    TrancheService: App\Service\TrancheService
```

## 🧪 Tests

### Tests API

```bash
# Tests avec curl
curl -X GET http://localhost:8000/api/client

# Tests avec VS Code REST Client
# Ouvrir les fichiers .http dans le dossier test/
```

### Fichiers de test disponibles

- `test/api_clients.http` - Tests des endpoints clients
- `test/api_compteurs.http` - Tests des endpoints compteurs
- `test/api_tranches.http` - Tests des endpoints tranches
- `test/api_achats.http` - Tests des endpoints achats
- `test_api_new_structure.http` - Tests complets de la nouvelle structure

## 🚀 Déploiement sur Render

### Configuration automatique

1. **Connectez votre repository à Render**
2. **Configurez les variables d'environnement** :

   - `DB_HOST`, `DB_PORT`, `DB_NAME`
   - `DB_USERNAME`, `DB_PASSWORD`
   - `URL` (URL de votre application Render)

3. **Déployez** : Render détectera automatiquement le `Dockerfile`

### Structure de déploiement

- **Point d'entrée** : `public/index.php`
- **Configuration Nginx** : Pointe vers `/var/www/html/public`
- **Docker** : Utilise PHP-FPM + Nginx

## 📈 Fonctionnalités

### Gestion des Clients

- Récupération automatique depuis l'API externe
- Informations complètes (nom, prénom, téléphone, CNI, adresse)
- Gestion des civilités

### Système de Compteurs

- Numérotation unique des compteurs
- Association avec les clients
- Suivi de la consommation mensuelle/annuelle
- Calcul automatique du statut de tranche
- Réinitialisation mensuelle

### Tranches Tarifaires

- 4 tranches selon les tarifs Sénénelec
- Prix progressifs par kWh
- Calcul automatique selon la consommation

### Achats d'Électricité

- Vérification de l'existence du compteur
- Calcul automatique selon les tranches
- Génération de codes de recharge
- Historique complet des transactions

## 🔍 Logs et Monitoring

- Toutes les demandes d'achat sont journalisées
- Logs d'erreur et de succès
- Traçabilité complète des transactions

## 📝 Format des Réponses API

### Succès

```json
{
  "data": {
    "compteur": "CPT001",
    "reference": "ACHAT001",
    "code": "CODE123",
    "date": "2025-01-28 10:30:00",
    "tranche": "Tranche 1",
    "prix": "91.00",
    "nbreKwt": "50.0",
    "client": "Nom Prénom"
  },
  "statut": "success",
  "code": 200,
  "message": "Achat effectué avec succès"
}
```

### Erreur

```json
{
  "data": null,
  "statut": "error",
  "code": 404,
  "message": "Le numéro de compteur non retrouvé"
}
```

## 🤝 Contribution

1. Fork le projet
2. Créez une branche feature (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.
