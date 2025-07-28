#!/bin/bash

echo "🧪 Test du déploiement Docker local..."

# Nettoyer les conteneurs existants
echo "🧹 Nettoyage des conteneurs existants..."
docker-compose down -v --remove-orphans 2>/dev/null || true
docker stop app-woyofall-test 2>/dev/null || true
docker rm app-woyofall-test 2>/dev/null || true

# Construire l'image
echo "🔨 Construction de l'image Docker..."
docker build -f Dockerfile.render -t app-woyofall-test .

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de la construction de l'image"
    exit 1
fi

# Démarrer le conteneur
echo "🚀 Démarrage du conteneur..."
docker run -d \
    --name app-woyofall-test \
    -p 8000:80 \
    -e DB_HOST=localhost \
    -e DB_PORT=5432 \
    -e DB_NAME=test \
    -e DB_USER=test \
    -e DB_PASSWORD=test \
    app-woyofall-test

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors du démarrage du conteneur"
    exit 1
fi

# Attendre que le conteneur soit prêt
echo "⏳ Attente du démarrage du conteneur..."
sleep 10

# Vérifier que le conteneur fonctionne
echo "🔍 Vérification du conteneur..."
if docker ps | grep -q app-woyofall-test; then
    echo "✅ Conteneur en cours d'exécution"
else
    echo "❌ Conteneur non trouvé"
    docker logs app-woyofall-test
    exit 1
fi

# Tester l'API
echo "🌐 Test de l'API..."
sleep 5

# Test simple
if curl -s http://localhost:8000/ > /dev/null; then
    echo "✅ Serveur web accessible"
else
    echo "❌ Serveur web non accessible"
    docker logs app-woyofall-test
    exit 1
fi

# Test de l'API
if curl -s http://localhost:8000/api/client > /dev/null; then
    echo "✅ API accessible"
else
    echo "⚠️  API non accessible (normal si pas de base de données)"
fi

echo "🎉 Test terminé avec succès !"
echo "📊 Logs du conteneur :"
docker logs app-woyofall-test

echo ""
echo "🔗 URL de test : http://localhost:8000"
echo "🛑 Pour arrêter : docker stop app-woyofall-test" 