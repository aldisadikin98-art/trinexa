#!/bin/bash
set -e

# Railway sets PORT env var — Apache must listen on it
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf

# Generate .env from environment variables if .env doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

# Override .env with actual environment variables for production
php artisan key:generate --force --no-interaction 2>/dev/null || true

# Ensure storage directories exist
mkdir -p storage/framework/{cache/data,sessions,views,testing} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Cache configuration for production
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction 2>/dev/null || true

echo "Starting Laravel on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$(( PORT + 0 ))