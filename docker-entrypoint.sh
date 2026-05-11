#!/bin/bash
set -e

# Cast PORT to integer
APP_PORT=$((${PORT:-8000} + 0))

# Generate .env from environment variables if .env doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

php artisan key:generate --force --no-interaction 2>/dev/null || true

# Ensure storage directories exist
mkdir -p storage/framework/{cache/data,sessions,views,testing} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan migrate --force --no-interaction 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true
php artisan optimize 2>/dev/null || true

echo "Starting Laravel on port $APP_PORT..."
exec php -S 0.0.0.0:$APP_PORT -t public