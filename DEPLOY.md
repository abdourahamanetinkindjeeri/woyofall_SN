# 🚀 Déploiement Rapide sur Render

## Étapes pour déployer

### 1. Préparer le repository

```bash
# Assurez-vous que tous les fichiers sont commités
git add .
git commit -m "Ready for Render deployment"
git push origin main
```

### 2. Sur Render.com

1. Allez sur [render.com](https://render.com)
2. Cliquez sur "New +" → "Web Service"
3. Connectez votre repository GitHub
4. Configurez :
   - **Name** : `app-woyofall`
   - **Environment** : `Docker`
   - **Branch** : `main`

### 3. Variables d'environnement

Dans l'onglet "Environment", ajoutez :

```
DB_HOST=votre_host_postgresql
DB_PORT=5432
DB_NAME=votre_nom_base
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### 4. Déployer

- Cliquez sur "Create Web Service"
- Render utilisera automatiquement `Dockerfile.render`

## Fichiers de configuration

### Dockerfile.render

- Basé sur `nginx:alpine`
- PHP 8.3 avec toutes les extensions nécessaires
- Configuration optimisée pour Render

### nginx.conf

- Configuration nginx compatible Alpine
- Support CORS pour l'API
- Redirection vers index.php

## Test après déploiement

```bash
# Testez votre API
curl https://votre-app.onrender.com/api/client
curl https://votre-app.onrender.com/api/tranche
curl https://votre-app.onrender.com/api/compteur
```

## Dépannage

Si le déploiement échoue :

1. Vérifiez les logs dans Render
2. Assurez-vous que les variables d'environnement sont correctes
3. Vérifiez que la base de données PostgreSQL est accessible

## Support

- [Documentation Render](https://render.com/docs)
- [Guide de dépannage](./TROUBLESHOOTING.md)
