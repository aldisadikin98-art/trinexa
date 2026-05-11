#!/bin/bash
set -e

# Use Railway's PORT or default to 80
if [ ! -z "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Generate .env from environment variables if .env doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || true
fi

# Generate app key if not set
php artisan key:generate --force --no-interaction 2>/dev/null || true

# Cache configuration for production
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction 2>/dev/null || true

# Start Apache
exec apache2-foreground
