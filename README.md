# AppWoyofall - Application de Gestion d'Électricité

## Description

Application PHP pour la gestion des achats d'électricité avec système de tranches tarifaires.

## Configuration de la Base de Données

### 1. Initialisation de la Base de Données

```bash
php setup_database.php
```

### 2. Test de Connexion

```bash
php test_connection.php
```

## Structure de la Base de Données

### Tables

- **client** : Informations des clients
- **tranche** : Tranches tarifaires d'électricité
- **achat** : Historique des achats d'électricité

### Données de Base

Les tranches tarifaires suivantes sont créées automatiquement (selon les tarifs Sénénelec) :

- Tranche 1 : 0-150 kWh à 91 FCFA/kWh
- Tranche 2 : 151-250 kWh à 102 FCFA/kWh
- Tranche 3 : 251-400 kWh à 116 FCFA/kWh
- Tranche 4 : 401+ kWh à 132 FCFA/kWh

**Note :** Les tranches se remettent à zéro chaque mois (souvent le 1er du mois).

## Fonctionnalités

### Gestion des Compteurs

- **Consommation mensuelle** : Suivi de la consommation par mois
- **Consommation annuelle** : Suivi de la consommation par année
- **Statut de tranche** : Calcul automatique de la tranche tarifaire selon la consommation
- **Mise à jour automatique** : La consommation est mise à jour après chaque achat

### Données de Test

Le système inclut 30 clients avec leurs compteurs et des données de consommation réalistes :

- Répartition par tranche tarifaire
- Consommations mensuelles et annuelles
- Statuts de tranche calculés automatiquement

## API Endpoints

### POST /api/client

Créer un nouveau client

```json
{
  "nom": "Doe",
  "prenom": "John",
  "telephone": "221701234567",
  "cni": "1234567890123",
  "adresse": "123 Rue Example, Dakar",
  "civilite": "M"
}
```

### POST /api/tranche

Ajouter une nouvelle tranche tarifaire

```json
{
  "nom": "Tranche 5",
  "min": 401,
  "max": 500,
  "prix_par_kwh": 125.0
}
```

### POST /api/achat

Effectuer un achat d'électricité

```json
{
  "client_id": 1,
  "compteur_numero": "COMP001",
  "nbre_kwt": 150.5
}
```

### GET /api/compteur

Récupérer tous les compteurs

### POST /api/compteur

Créer un nouveau compteur

```json
{
  "client_id": 1,
  "tranche_consommee": 0.0,
  "mois_courant": "2024-01"
}
```

### GET /api/compteur/{numero}

Récupérer un compteur par numéro

### PUT /api/compteur/{numero}

Mettre à jour un compteur

### DELETE /api/compteur/{numero}

Supprimer un compteur

### GET /api/compteur/client/{client_id}

Récupérer les compteurs d'un client

### PUT /api/compteur/{numero}/consommation

Mettre à jour la consommation d'un compteur

```json
{
  "kwh": 25.5
}
```

### POST /api/compteur/{numero}/reset

Réinitialiser la consommation d'un compteur

## Architecture

### Structure des Dossiers

```
src/
├── controller/     # Contrôleurs
├── entity/         # Entités
├── repository/     # Couche d'accès aux données
├── service/        # Logique métier
└── enum/          # Énumérations
```

### Pattern Utilisé

- **Repository Pattern** : Séparation de la logique d'accès aux données
- **Service Pattern** : Logique métier centralisée
- **Interface Pattern** : Contrats pour les dépendances

## Dépendances

- PHP 8.0+
- PostgreSQL
- Composer (pour l'autoloading)

## Installation

1. Cloner le projet
2. Installer les dépendances : `composer install`
3. Configurer les variables d'environnement :
   ```bash
   cp env.example .env
   # Éditer le fichier .env avec vos vraies informations de connexion
   ```
4. Configurer la base de données : `php setup_database.php`
5. Tester la connexion : `php test_connection.php`
6. Démarrer le serveur : `php -S localhost:8000`

## Configuration

Les paramètres de connexion à la base de données sont dans le fichier `.env`.
Copiez `env.example` vers `.env` et configurez vos variables d'environnement.

**⚠️ Important :** Le fichier `.env` contient des informations sensibles et ne doit jamais être commité dans Git.
