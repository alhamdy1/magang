#!/bin/bash

# Render.com startup script
# Similar to railway-start.sh but optimized for Render

set -e

echo "=== Starting Render.com Deployment ==="

# Create necessary directories
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache database

# Set proper permissions
chmod -R 775 storage bootstrap/cache database

# Create SQLite database if using SQLite and it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    if [ ! -f database/database.sqlite ]; then
        echo "Creating SQLite database..."
        touch database/database.sqlite
        chmod 664 database/database.sqlite
    fi
fi

# Generate APP_KEY if not set (Render auto-generates this)
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
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
echo "Checking if database needs seeding..."
if php artisan tinker --no-ansi --execute="exit(\App\Models\User::count() > 0 ? 0 : 1);" 2>/dev/null; then
    echo "Database already has data, skipping seed..."
else
    echo "Seeding database with initial data..."
    php artisan db:seed --force
fi

# Start the Laravel application
echo "Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
