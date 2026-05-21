# ============================================================
# Dockerfile – School Grades System (PHP + Apache + PostgreSQL)
# Compatible with Render.com (Web Service – Docker)
# ============================================================

# Base image: official PHP 8.3 with Apache
FROM php:8.3-apache

# ── System dependencies ──────────────────────────────────────
RUN apt-get update && apt-get install -y \
        libpq-dev \
        unzip \
        curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ── Apache: enable mod_rewrite (optional but good practice) ──
RUN a2enmod rewrite

# ── Apache: configure document root ─────────────────────────
# All requests go to index.php in the project root
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Allow .htaccess overrides
RUN sed -i 's|AllowOverride None|AllowOverride All|g' \
        /etc/apache2/apache2.conf

# ── Copy application files ───────────────────────────────────
COPY . /var/www/html/

# Set correct ownership so Apache can read the files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ── Environment variables (set these in Render dashboard) ────
# Render injects these at runtime; defaults here are placeholders.
ENV DB_HOST=aws-1-us-east-2.pooler.supabase.com \
    DB_PORT=6543 \
    DB_NAME=postgres \
    DB_USER=postgres.syiqftmpmmpvjbpmqspp \
    DB_PASS=Alexander12.Dalmatax1011 \
    DB_SCHEMA=public

# ── Expose port (Render maps this automatically) ─────────────
EXPOSE 80

# Apache starts in foreground (required for Docker)
CMD ["apache2-foreground"]
