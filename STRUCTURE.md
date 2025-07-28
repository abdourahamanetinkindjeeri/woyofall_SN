# Structure du Projet Woyofall

## Nouvelle Architecture

Le projet a été restructuré pour utiliser une architecture moderne avec injection de dépendances et gestion des routes via YAML.

### Structure des Dossiers

```
woyofall/
├── app/
│   ├── config/
│   │   ├── bootstrap.php      # Configuration de démarrage
│   │   ├── dependencies.yaml  # Dépendances de l'application
│   │   ├── env.php           # Variables d'environnement
│   │   ├── helpers.php       # Fonctions utilitaires
│   │   └── routes.php        # Configuration des routes
│   └── core/
│       ├── App.php           # Classe principale de l'application
│       ├── Container.php     # Conteneur de dépendances
│       └── Router.php        # Routeur moderne
├── src/
│   ├── controller/           # Contrôleurs
│   ├── entity/              # Entités
│   ├── repository/          # Repositories
│   └── service/             # Services
├── public/
│   └── index.php            # Point d'entrée de l'application
└── config/
    └── database.php         # Configuration de la base de données
```

### Gestion des Dépendances

Le fichier `app/config/dependencies.yaml` définit toutes les dépendances de l'application :

```yaml
dependencies:
  controllers:
    AchatController: App\Controller\AchatController
    CompteurController: App\Controller\CompteurController
    ClientController: App\Controller\ClientController
    TrancheController: App\Controller\TrancheController

  services:
    AchatService: App\Service\AchatService
    ClientService: App\Service\ClientService
    CompteurService: App\Service\CompteurService
    TrancheService: App\Service\TrancheService

  repositories:
    AchatRepository: App\Repository\AchatRepository
    ClientRepository: App\Repository\ClientRepository
    CompteurRepository: App\Repository\CompteurRepository
    TrancheRepository: App\Repository\TrancheRepository

  core:
    Database: App\Core\Database
    Router: App\Core\Router
    App: App\Core\App
    PDO: PDO
```

### Conteneur de Dépendances

La classe `Container` gère l'injection automatique des dépendances :

- **Singleton Pattern** : Une seule instance du conteneur
- **Injection automatique** : Les dépendances sont résolues automatiquement
- **Gestion PDO** : Configuration automatique de la connexion à la base de données

### Routeur Moderne

La classe `Router` offre :

- **Méthodes HTTP** : GET, POST, PUT, DELETE
- **Paramètres d'URL** : Support des paramètres `{id}`
- **Gestion d'erreurs** : Réponses JSON standardisées
- **Injection de contrôleurs** : Via le conteneur de dépendances

### Configuration des Routes

Les routes sont définies dans `app/config/routes.php` :

```php
return [
    'api' => [
        'achat' => [
            'GET' => ['AchatController', 'getAllAchats'],
            'POST' => ['AchatController', 'acheter']
        ],
        // ... autres routes
    ]
];
```

### Point d'Entrée

Le fichier `public/index.php` est le nouveau point d'entrée :

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Démarrer l'application
$app = App\Core\App::getInstance();
$app->run();
```

### Avantages de la Nouvelle Structure

1. **Séparation des responsabilités** : Chaque classe a une responsabilité claire
2. **Injection de dépendances** : Couplage faible entre les composants
3. **Configuration centralisée** : Toutes les dépendances dans un fichier YAML
4. **Routes déclaratives** : Configuration des routes dans un fichier dédié
5. **Gestion d'erreurs** : Réponses JSON standardisées
6. **Extensibilité** : Facile d'ajouter de nouveaux contrôleurs et services

### Installation et Utilisation

1. **Installer les dépendances** :

   ```bash
   composer install
   ```

2. **Configurer l'environnement** :

   ```bash
   cp env.example .env
   # Éditer .env avec vos paramètres
   ```

3. **Démarrer le serveur** :

   ```bash
   php -S localhost:8000 -t public
   ```

4. **Tester l'API** :
   ```bash
   curl http://localhost:8000/api/client
   ```

### Migration depuis l'Ancienne Structure

L'ancien fichier `index.php` a été remplacé par :

- `public/index.php` : Point d'entrée
- `app/core/App.php` : Orchestration de l'application
- `app/core/Container.php` : Gestion des dépendances
- `app/core/Router.php` : Routage des requêtes

Tous les contrôleurs existants ont été adaptés pour utiliser la nouvelle structure d'injection de dépendances.
