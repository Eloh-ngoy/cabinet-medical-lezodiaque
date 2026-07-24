#!/bin/bash
set -e

echo "=== MediNexus Starting ==="
echo "APP_KEY length: ${#APP_KEY}"

# Wait for PostgreSQL
if [ -n "$DB_URL" ] || [ -n "$DATABASE_URL" ]; then
    DB_CONNECTION_URL="${DB_URL:-$DATABASE_URL}"
    echo "Waiting for PostgreSQL..."
    for i in $(seq 1 30); do
        php -r "try { new PDO('$DB_CONNECTION_URL'); exit(0); } catch(Exception \$e) { exit(1); }" 2>/dev/null && break
        echo "  Attempt $i/30 - DB not ready yet..."
        sleep 2
    done
fi

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database and permissions..."
php artisan db:seed --force
php artisan permission:cache-reset

echo "Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "=== Starting Apache ==="
exec apache2-foreground