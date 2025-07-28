# Déploiement de Woyofall - Nouvelle Structure

## 🚀 Déploiement Local avec Docker

### Prérequis

- Docker installé
- Variables d'environnement configurées

### Déploiement Rapide

1. **Cloner le projet** :

   ```bash
   git clone <repository>
   cd woyofall
   ```

2. **Configurer l'environnement** :

   ```bash
   cp env.example .env
   # Éditer .env avec vos paramètres de base de données
   ```

3. **Déployer avec Docker** :

   ```bash
   chmod +x deploy_new_structure.sh
   ./deploy_new_structure.sh
   ```

4. **Accéder à l'application** :
   - Application : http://localhost:8080
   - API : http://localhost:8080/api/client

## 🐳 Déploiement Manuel avec Docker

### Construction de l'image

```bash
docker build -t woyofall-new .
```

### Démarrage du conteneur

```bash
docker run -d \
    --name woyofall-new \
    -p 8080:80 \
    -e DB_HOST=your_db_host \
    -e DB_PORT=your_db_port \
    -e DB_NAME=your_db_name \
    -e DB_USERNAME=your_username \
    -e DB_PASSWORD=your_password \
    woyofall-new
```

## ☁️ Déploiement sur Render

### Configuration Render

1. **Connecter votre repository** à Render
2. **Créer un nouveau service Web**
3. **Configuration** :
   - **Build Command** : `docker build -f Dockerfile.render -t woyofall-new .`
   - **Start Command** : `docker run -p 80:80 woyofall-new`
   - **Dockerfile Path** : `./Dockerfile.render`

### Variables d'environnement sur Render

Configurez ces variables dans votre dashboard Render :

```
DB_HOST=your_database_host
DB_PORT=your_database_port
DB_NAME=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
```

## 🔧 Configuration de la Base de Données

### PostgreSQL (Recommandé)

```sql
-- Créer la base de données
CREATE DATABASE woyofall;

-- Créer les tables (voir database.sql)
```

### Variables d'environnement requises

```env
# Base de données
DB_HOST=your_host
DB_PORT=5432
DB_NAME=woyofall
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Configuration pour app/config/env.php
URL=https://your-domain.com
DB_USER=your_username
DB_PASS=your_password
DSN=pgsql:host=your_host;port=5432;dbname=woyofall
CLOUD_NAME=your_cloudinary_name
API_KEY=your_cloudinary_key
API_SECRET=your_cloudinary_secret
```

## 🏗️ Structure de Déploiement

### Nouvelle Architecture

```
woyofall/
├── public/
│   └── index.php          # Point d'entrée principal
├── app/
│   ├── config/
│   │   ├── dependencies.yaml  # Configuration des dépendances
│   │   └── env.php           # Variables d'environnement
│   └── core/
│       ├── App.php           # Application principale
│       ├── Container.php     # Conteneur de dépendances
│       └── Router.php        # Routeur moderne
├── src/
│   ├── controller/          # Contrôleurs
│   ├── service/            # Services
│   └── repository/         # Repositories
└── routes/
    └── route.web.php       # Configuration des routes
```

### Avantages de la Nouvelle Structure

1. **Séparation claire** : Point d'entrée dans `public/`
2. **Injection de dépendances** : Gestion automatique via YAML
3. **Configuration centralisée** : Toutes les dépendances dans un fichier
4. **Routes déclaratives** : Configuration des routes dans un fichier dédié
5. **Extensibilité** : Facile d'ajouter de nouveaux services

## 🔍 Tests de Déploiement

### Test de l'API

```bash
# Test des clients
curl http://localhost:8080/api/client

# Test des compteurs
curl http://localhost:8080/api/compteur

# Test des achats
curl -X POST http://localhost:8080/api/achat \
  -H "Content-Type: application/json" \
  -d '{"numero_compteur":"123","montant":100,"client_id":1}'
```

### Vérification des logs

```bash
# Voir les logs du conteneur
docker logs woyofall-new

# Voir les logs en temps réel
docker logs -f woyofall-new
```

## 🛠️ Dépannage

### Problèmes courants

1. **Erreur de connexion à la base de données**

   - Vérifier les variables d'environnement
   - Vérifier que la base de données est accessible

2. **Erreur 404 sur les routes**

   - Vérifier que le fichier `routes/route.web.php` existe
   - Vérifier la configuration Nginx

3. **Erreur de dépendances**
   - Vérifier le fichier `app/config/dependencies.yaml`
   - Vérifier que tous les services sont définis

### Commandes utiles

```bash
# Reconstruire l'image
docker build --no-cache -t woyofall-new .

# Redémarrer le conteneur
docker restart woyofall-new

# Accéder au conteneur
docker exec -it woyofall-new bash

# Voir les processus
docker top woyofall-new
```

## 📝 Notes de Migration

### Changements depuis l'ancienne structure

1. **Point d'entrée** : `index.php` → `public/index.php`
2. **Configuration** : Variables dans `app/config/env.php`
3. **Routes** : Configuration dans `routes/route.web.php`
4. **Dépendances** : Gestion via `app/config/dependencies.yaml`

### Compatibilité

La nouvelle structure est compatible avec :

- ✅ Docker
- ✅ Render
- ✅ PostgreSQL
- ✅ Nginx
- ✅ PHP 8.1+

## 🎉 Conclusion

La nouvelle structure offre une meilleure organisation, une gestion des dépendances plus robuste et une extensibilité accrue tout en maintenant la compatibilité avec les plateformes de déploiement existantes.
