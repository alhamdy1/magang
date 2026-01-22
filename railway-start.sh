#!/bin/bash

# Railway startup script
# This script handles database migrations and starts the Laravel application

set -e

echo "=== Starting Railway Deployment ==="

# Create necessary directories
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache

# Create SQLite database if using SQLite and it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    if [ ! -f database/database.sqlite ]; then
        echo "Creating SQLite database..."
        touch database/database.sqlite
    fi
fi

# Clear and cache configuration for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Create storage link if it doesn't exist
if [ ! -L public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link || true
fi

# Seed database if running for first time (check if users table is empty)
if php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | grep -q "^0$"; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

# Start the Laravel application
echo "Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
