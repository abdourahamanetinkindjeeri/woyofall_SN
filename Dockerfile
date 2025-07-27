FROM php:8.1-fpm

# Installer Nginx, Composer et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    curl \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers de l'application
COPY . /var/www/html/

# Installer les dépendances Composer
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Créer le fichier .env par défaut dans le répertoire parent
RUN printf "# Configuration de base de données pour Docker\nDB_HOST=localhost\nDB_PORT=5432\nDB_NAME=woyofall\nDB_USER=postgres\nDB_USERNAME=postgres\nDB_PASSWORD=password\n\n# Configuration de l'application\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_URL=http://localhost\n" > /var/www/.env

# Copier la configuration Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Créer un script de démarrage plus robuste
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'set -e' >> /start.sh && \
    echo 'echo "Starting nginx..."' >> /start.sh && \
    echo 'nginx -g "daemon off;" &' >> /start.sh && \
    echo 'echo "Starting PHP-FPM..."' >> /start.sh && \
    echo 'php-fpm' >> /start.sh && \
    chmod +x /start.sh

# Exposer le port
EXPOSE 80

# Commande de démarrage
CMD ["/start.sh"] 