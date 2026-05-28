FROM php:8.2-apache

# 1. Dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# 2. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copiar proyecto e instalar dependencias
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

USER www-data
RUN composer install --no-interaction --optimize-autoloader --no-dev
USER root

# 4. Document root apunta a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. AllowOverride All para que el .htaccess funcione en Render
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 6. Render asigna el puerto dinámicamente via $PORT (por defecto 10000).
#    Este script arranca Apache escuchando en ese puerto.
RUN chmod -R 775 /var/www/html/storage 2>/dev/null || true

CMD ["/bin/bash", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-80}/g\" /etc/apache2/ports.conf && sed -i \"s/:80/:${PORT:-80}/g\" /etc/apache2/sites-available/*.conf && apache2-foreground"]

EXPOSE 10000