#!/bin/bash
set -e

echo "=== MediNexus Starting ==="

# Wait for PostgreSQL
if [ -n "$DATABASE_URL" ]; then
    echo "Waiting for PostgreSQL..."
    for i in $(seq 1 30); do
        php -r "try { new PDO('$DATABASE_URL'); exit(0); } catch(Exception \$e) { exit(1); }" 2>/dev/null && break
        echo "  Attempt $i/30 - DB not ready yet..."
        sleep 2
    done
fi

echo "Running migrations..."
php artisan migrate --force

echo "Clearing Laravel cache..."
php artisan optimize:clear || true

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "=== Starting Apache ==="
exec apache2-foreground