#!/bin/bash

echo "🧪 Test de l'API Woyofall sur Render..."

# URL de l'API Render
RENDER_URL="https://woyofall-sn-1.onrender.com"

echo "📡 Test de l'endpoint /api/client..."
curl -X GET "$RENDER_URL/api/client" \
  -H "Content-Type: application/json" \
  -w "\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "📡 Test de l'endpoint /api/compteur..."
curl -X GET "$RENDER_URL/api/compteur" \
  -H "Content-Type: application/json" \
  -w "\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "📡 Test de l'endpoint /api/tranche..."
curl -X GET "$RENDER_URL/api/tranche" \
  -H "Content-Type: application/json" \
  -w "\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "📡 Test de l'endpoint /api/achat..."
curl -X GET "$RENDER_URL/api/achat" \
  -H "Content-Type: application/json" \
  -w "\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "🔍 Test de debug - vérification du fichier index.php..."
curl -X GET "$RENDER_URL/test.php" \
  -w "\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "✅ Tests terminés !" 