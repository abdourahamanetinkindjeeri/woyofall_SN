# 🔧 Guide de Dépannage Render

## Erreurs courantes et solutions

### 1. "nginx failed!" - Nginx ne démarre pas

**Problème :** Nginx ne peut pas démarrer en tant que service système dans un conteneur.

**Solutions :**

- Utiliser `nginx -g "daemon off;"` au lieu de `service nginx start`
- Utiliser supervisor pour gérer les processus
- Utiliser une image Alpine avec nginx + PHP intégré

### 2. "No open HTTP ports detected" - Aucun port HTTP ouvert

**Problème :** Render ne détecte aucun port HTTP ouvert.

**Solutions :**

- Vérifier que le port 80 est bien exposé dans le Dockerfile
- S'assurer que nginx écoute sur le port 80
- Vérifier que le script de démarrage fonctionne correctement

### 3. "Port scan timeout reached" - Timeout du scan de port

**Problème :** L'application ne démarre pas assez vite.

**Solutions :**

- Optimiser le Dockerfile pour un build plus rapide
- Utiliser des images de base plus légères (Alpine)
- Vérifier que tous les services démarrent correctement

## Versions Dockerfile disponibles

### 1. Dockerfile (version originale)

- Basé sur php:8.1-fpm
- Utilise nginx + PHP-FPM
- Peut avoir des problèmes de démarrage

### 2. Dockerfile.supervisor (version avec supervisor)

- Utilise supervisor pour gérer les processus
- Plus robuste pour la gestion des services
- Plus complexe mais plus fiable

### 3. Dockerfile.simple (version Alpine)

- Basé sur nginx:alpine
- PHP intégré dans l'image Alpine
- Plus léger et plus rapide
- Recommandé pour Render

## Configuration recommandée

Pour Render, utilisez `Dockerfile.simple` avec `nginx.alpine.conf` :

```yaml
# render.yaml
services:
  - type: web
    name: app-woyofall
    runtime: docker
    dockerfilePath: ./Dockerfile.simple
```

## Variables d'environnement

Assurez-vous d'avoir configuré ces variables dans Render :

```
DB_HOST=votre_host_postgresql
DB_PORT=5432
DB_NAME=votre_nom_base
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

## Test local

Pour tester localement avant de déployer :

```bash
# Construire l'image
docker build -f Dockerfile.simple -t app-woyofall .

# Tester l'image
docker run -p 8000:80 app-woyofall

# Tester l'API
curl http://localhost:8000/api/client
```

## Logs utiles

Pour diagnostiquer les problèmes :

```bash
# Voir les logs du conteneur
docker logs <container_id>

# Voir les logs nginx
docker exec <container_id> tail -f /var/log/nginx/error.log

# Voir les logs PHP-FPM
docker exec <container_id> tail -f /var/log/php-fpm.log
```

## Commandes de débogage

```bash
# Entrer dans le conteneur
docker exec -it <container_id> sh

# Vérifier les processus
ps aux

# Vérifier les ports
netstat -tlnp

# Tester nginx
nginx -t
```
