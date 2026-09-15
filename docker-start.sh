#!/bin/bash
set -e

echo "=== MediNexus Starting ==="
echo "APP_KEY length: ${#APP_KEY}"

# Configure Apache to listen on Render-provided $PORT (default 80)
PORT=${PORT:-80}
echo "Configuring Apache to listen on port $PORT"
sed -ri "s/^Listen\s+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf || true
for f in /etc/apache2/sites-available/*.conf; do
    sed -ri "s/<VirtualHost \*:([0-9]+)>/<VirtualHost *:${PORT}>/" "$f" || true
done

# Background job: wait for DB and run migrations/seeders repeatedly until success
wait_and_migrate() {
    cd /var/www/html || return
    if [ -z "$DB_URL" ] && [ -z "$DATABASE_URL" ]; then
        echo "No DATABASE_URL/DB_URL set — skipping DB migrations in background."
        return
    fi

    echo "Background DB watcher started..."
    # Initial aggressive attempts
    for i in $(seq 1 60); do
        php -r '
            $url = getenv("DB_URL") ?: getenv("DATABASE_URL");
            if (!$url) exit(1);
            $parts = parse_url($url);
            if ($parts === false || !isset($parts["host"])) exit(1);
            $host = $parts["host"] ?? "";
            $port = $parts["port"] ?? 5432;
            $user = $parts["user"] ?? null;
            $pass = $parts["pass"] ?? null;
            $path = $parts["path"] ?? null;
            $dbname = $path ? ltrim($path, "/") : null;
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            try { new PDO($dsn, $user, $pass); exit(0); } catch (Exception $e) { exit(1); }
        ' 2>/dev/null && {
            echo "DB is reachable, running migrations..."
            php artisan migrate --force && php artisan db:seed --force && php artisan permission:cache-reset || echo 'Migrations/seeders may have failed; continuing background retries.'
            # After successful migration run once, exit background job
            echo "Migrations executed successfully."
            return
        }
        echo "  Attempt $i/60 - DB not ready yet..."
        sleep 5
    done

    # If initial attempts failed, switch to indefinite retry with longer interval
    echo "Database not ready after initial attempts — switching to infinite retry every 30s."
    while true; do
        php -r '
            $url = getenv("DB_URL") ?: getenv("DATABASE_URL");
            if (!$url) exit(1);
            $parts = parse_url($url);
            if ($parts === false || !isset($parts["host"])) exit(1);
            $host = $parts["host"] ?? "";
            $port = $parts["port"] ?? 5432;
            $user = $parts["user"] ?? null;
            $pass = $parts["pass"] ?? null;
            $path = $parts["path"] ?? null;
            $dbname = $path ? ltrim($path, "/") : null;
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            try { new PDO($dsn, $user, $pass); exit(0); } catch (Exception $e) { exit(1); }
        ' 2>/dev/null && {
            echo "DB is reachable, running migrations..."
            php artisan migrate --force && php artisan db:seed --force && php artisan permission:cache-reset || echo 'Migrations/seeders may have failed; will retry.'
            echo "Migrations executed successfully (background)."
            return
        }
        echo "  DB still not reachable, sleeping 30s before retry..."
        sleep 30
    done
}

# Start background migration runner (non-blocking)
wait_and_migrate &

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