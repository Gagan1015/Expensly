#!/bin/bash
set -e

echo "Starting application initialization..."

# Create database directory if it doesn't exist
mkdir -p /var/www/html/database

# Check if database file exists and has data
if [ ! -f /var/www/html/database/database.sqlite ] || [ ! -s /var/www/html/database/database.sqlite ]; then
    echo "Database not found or empty. Creating and seeding database..."
    touch /var/www/html/database/database.sqlite
    
    # Set permissions
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 666 /var/www/html/database/database.sqlite
    
    # Run migrations
    php artisan migrate --force
    
    # Seed the database
    php artisan db:seed --force
    
    echo "Database created and seeded successfully!"
else
    echo "Database exists. Running migrations (if any)..."
    php artisan migrate --force
    echo "Migrations completed!"
fi

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application initialized successfully!"

# Start Apache
exec apache2-foreground
