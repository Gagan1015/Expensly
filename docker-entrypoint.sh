#!/bin/bash
set -e

echo "Starting application initialization..."

# Determine if we're using SQLite or PostgreSQL
if [ -n "$DATABASE_URL" ]; then
    echo "Using PostgreSQL database from DATABASE_URL"
    DB_TYPE="pgsql"
else
    echo "Using SQLite database"
    DB_TYPE="sqlite"
    
    # Create database directory for SQLite
    mkdir -p /var/www/html/database
    
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        echo "Creating SQLite database file..."
        touch /var/www/html/database/database.sqlite
        chown www-data:www-data /var/www/html/database/database.sqlite
        chmod 666 /var/www/html/database/database.sqlite
    fi
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Check if we need to seed (only if users table is empty)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();")
if [ "$USER_COUNT" = "0" ]; then
    echo "Database is empty. Seeding initial data..."
    php artisan db:seed --force
    echo "Database seeded successfully!"
else
    echo "Database already has data. Skipping seeding."
fi

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application initialized successfully!"

# Start Apache
exec apache2-foreground
