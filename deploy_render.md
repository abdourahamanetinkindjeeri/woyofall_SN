# 🚀 Guide de Déploiement sur Render

## Prérequis

1. **Compte Render** : Créez un compte sur [render.com](https://render.com)
2. **Repository Git** : Votre code doit être sur GitHub/GitLab
3. **Base de données PostgreSQL** : Créez une base sur Railway ou Render

## Étapes de déploiement

### 1. Préparer le repository

Assurez-vous que votre repository contient ces fichiers :

- `Dockerfile`
- `nginx.conf`
- `render.yaml`
- `composer.json`
- `.env.example`

### 2. Créer le service sur Render

1. Connectez-vous à [render.com](https://render.com)
2. Cliquez sur "New +" → "Web Service"
3. Connectez votre repository GitHub/GitLab
4. Configurez le service :
   - **Name** : `app-woyofall`
   - **Environment** : `Docker`
   - **Region** : Choisissez la plus proche
   - **Branch** : `main` ou `master`

### 3. Configurer les variables d'environnement

Dans l'onglet "Environment" de votre service, ajoutez :

```
DB_HOST=votre_host_postgresql
DB_PORT=5432
DB_NAME=votre_nom_base
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### 4. Déployer

1. Cliquez sur "Create Web Service"
2. Render va automatiquement :
   - Détecter le `Dockerfile`
   - Construire l'image Docker
   - Déployer l'application
   - Fournir une URL publique

### 5. Configurer la base de données

1. **Sur Railway** :

   - Créez un nouveau projet PostgreSQL
   - Récupérez les informations de connexion
   - Ajoutez-les dans les variables d'environnement Render

2. **Sur Render** :
   - Créez un nouveau service PostgreSQL
   - Utilisez les variables d'environnement fournies

### 6. Tester l'API

Une fois déployé, testez votre API :

```bash
# Récupérer tous les clients
curl https://votre-app.onrender.com/api/client

# Récupérer toutes les tranches
curl https://votre-app.onrender.com/api/tranche

# Récupérer tous les compteurs
curl https://votre-app.onrender.com/api/compteur
```

## Configuration avancée

### Variables d'environnement supplémentaires

Vous pouvez ajouter ces variables optionnelles :

```
PHP_VERSION=8.1
APP_ENV=production
APP_DEBUG=false
```

### Domaine personnalisé

1. Dans les paramètres du service
2. Allez dans "Custom Domains"
3. Ajoutez votre domaine
4. Configurez les DNS selon les instructions

## Monitoring et logs

- **Logs** : Disponibles dans l'onglet "Logs" de Render
- **Metrics** : Monitoring automatique des performances
- **Health checks** : Vérifications automatiques de santé

## Dépannage

### Problèmes courants

1. **Build échoue** :

   - Vérifiez que le `Dockerfile` est correct
   - Consultez les logs de build

2. **Erreur de base de données** :

   - Vérifiez les variables d'environnement
   - Testez la connexion à la base

3. **Application ne démarre pas** :
   - Vérifiez les logs de l'application
   - Assurez-vous que le port 80 est exposé

### Commandes utiles

```bash
# Tester la connexion à la base
psql postgresql://user:pass@host:port/db

# Vérifier les logs
# (via l'interface Render)
```

## Coûts

- **Free tier** : Gratuit avec limitations
- **Paid plans** : À partir de $7/mois
- **Base de données** : Gratuite sur Railway, payante sur Render

## Support

- [Documentation Render](https://render.com/docs)
- [Support Render](https://render.com/support)
- [Community Render](https://community.render.com)
