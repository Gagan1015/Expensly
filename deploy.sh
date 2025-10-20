#!/bin/bash

# Deployment script for Expensly
echo "Starting deployment..."

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets with production configuration
NODE_ENV=production npm run build

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

echo "Deployment completed!"
