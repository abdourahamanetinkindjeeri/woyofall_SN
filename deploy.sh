 #!/bin/bash

echo "🚀 Déploiement sur Render..."

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Configurer la base de données si nécessaire
if [ -f "setup_database.php" ]; then
    echo "📊 Configuration de la base de données..."
    php setup_database.php
fi

# Démarrer le serveur
echo "🌐 Démarrage du serveur..."
php -S 0.0.0.0:$PORT index.php 