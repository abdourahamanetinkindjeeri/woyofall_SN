FROM php:8.1-fpm

# Installer Nginx et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier les fichiers de l'application
COPY . /var/www/html/

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