FROM php:8.2-apache

RUN apt-get update && apt-get install -y libzip-dev zip unzip curl sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip \
    && a2enmod rewrite

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set Apache ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

# Set Apache document root to Laravel's public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Create .env file from .env.example
RUN cp .env.example .env

# Set environment to production and use SQLite
RUN sed -i 's/APP_ENV=local/APP_ENV=production/' .env \
    && sed -i 's/APP_DEBUG=true/APP_DEBUG=true/' .env \
    && sed -i 's|APP_URL=http://localhost|APP_URL=https://expensly-krg5.onrender.com|' .env \
    && sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env \
    && sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/' .env \
    && sed -i 's/CACHE_STORE=database/CACHE_STORE=file/' .env \
    && sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=sync/' .env \
    && echo "" >> .env \
    && echo "# Force HTTPS in production" >> .env \
    && echo "ASSET_URL=https://expensly-krg5.onrender.com" >> .env \
    && echo "TRUSTED_PROXIES=*" >> .env

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN npm install && npm run build

# Create SQLite database
RUN touch /var/www/html/database/database.sqlite

# Generate application key
RUN php artisan key:generate --force

# Run migrations
RUN php artisan migrate --force

# Seed the database with admin account and sample data
RUN php artisan db:seed --force

# Cache Laravel configuration
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Set proper permissions - CRITICAL: Do this LAST to ensure all files have correct permissions
# SQLite needs write access to both the database file AND the directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database \
    && chmod 666 /var/www/html/database/database.sqlite

CMD ["apache2-foreground"]