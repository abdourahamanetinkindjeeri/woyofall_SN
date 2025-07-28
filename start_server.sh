#!/bin/bash

# Script pour démarrer le serveur PHP sur un port disponible
echo "🚀 Démarrage du serveur PHP..."

# Vérifier si le port 8000 est disponible
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null ; then
    echo "⚠️  Le port 8000 est déjà utilisé. Tentative sur le port 8001..."
    if lsof -Pi :8001 -sTCP:LISTEN -t >/dev/null ; then
        echo "⚠️  Le port 8001 est aussi utilisé. Tentative sur le port 8002..."
        php -S localhost:8002
    else
        php -S localhost:8001
    fi
else
    php -S localhost:8000
fi 