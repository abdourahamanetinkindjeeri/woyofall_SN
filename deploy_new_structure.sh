#!/bin/bash

echo "🚀 Déploiement de Woyofall avec la nouvelle structure..."

# Vérifier que Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker d'abord."
    exit 1
fi

# Construire l'image Docker
echo "📦 Construction de l'image Docker..."
docker build -t woyofall-new .

# Arrêter et supprimer le conteneur existant s'il existe
echo "🛑 Arrêt du conteneur existant..."
docker stop woyofall-new 2>/dev/null || true
docker rm woyofall-new 2>/dev/null || true

# Démarrer le nouveau conteneur
echo "🚀 Démarrage du nouveau conteneur..."
docker run -d \
    --name woyofall-new \
    -p 8080:80 \
    -e DB_HOST=${DB_HOST:-localhost} \
    -e DB_PORT=${DB_PORT:-5432} \
    -e DB_NAME=${DB_NAME:-woyofall} \
    -e DB_USERNAME=${DB_USERNAME:-postgres} \
    -e DB_PASSWORD=${DB_PASSWORD:-password} \
    woyofall-new

# Attendre que le conteneur soit prêt
echo "⏳ Attente du démarrage du conteneur..."
sleep 5

# Vérifier que le conteneur fonctionne
if docker ps | grep -q woyofall-new; then
    echo "✅ Conteneur démarré avec succès!"
    echo "🌐 Application accessible sur: http://localhost:8080"
    echo "📊 API accessible sur: http://localhost:8080/api/client"
else
    echo "❌ Erreur lors du démarrage du conteneur"
    docker logs woyofall-new
    exit 1
fi

echo "🎉 Déploiement terminé avec succès!" 