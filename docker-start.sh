#!/bin/bash
set -e

echo "=== MediNexus Starting ==="

# Wait for PostgreSQL to be ready
if [ -n "$DATABASE_URL" ]; then
    echo "Waiting for PostgreSQL..."
    for i in $(seq 1 30); do
        php -r "try { new PDO('$DATABASE_URL'); exit(0); } catch(Exception \$e) { exit(1); }" 2>/dev/null && break
        echo "  Attempt $i/30 - DB not ready yet..."
        sleep 2
    done
    echo "PostgreSQL is ready."
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed only if database is empty (check if users table has records)
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "Database is empty. Running seeders..."
    php artisan db:seed --force
    echo "Seeders completed."
else
    echo "Database already has $USER_COUNT users. Skipping seed."
fi

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "=== Starting Apache ==="
apache2-foreground
