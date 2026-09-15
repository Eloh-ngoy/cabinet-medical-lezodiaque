#!/bin/bash
set -e

echo "=== MediNexus Starting ==="
echo "APP_KEY length: ${#APP_KEY}"

# Sanitize DATABASE_URL/DB_URL (remove CR/LF that can break hostnames)
if [ -n "$DATABASE_URL" ]; then
    # Remove CR/LF, surrounding quotes and stray whitespace
    export DATABASE_URL="$(printf "%s" "$DATABASE_URL" | tr -d '\r\n' | sed -E "s/^[[:space:]\"']+//; s/[[:space:]\"']+$//; s/[[:space:]]+//g")"
    echo "Sanitized DATABASE_URL"
fi
if [ -n "$DB_URL" ]; then
    export DB_URL="$(printf "%s" "$DB_URL" | tr -d '\r\n' | sed -E "s/^[[:space:]\"']+//; s/[[:space:]\"']+$//; s/[[:space:]]+//g")"
    echo "Sanitized DB_URL"
fi

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
    diagnose_db() {
        php -r '
            $url = getenv("DB_URL") ?: getenv("DATABASE_URL");
            $parts = $url ? parse_url($url) : false;
            if ($parts === false) { echo "[diag] parse_url failed\n"; exit(1); }
            $host = $parts["host"] ?? getenv("DB_HOST") ?? "";
            $port = $parts["port"] ?? getenv("DB_PORT") ?? 5432;
            echo "[diag] DB host={$host} port={$port}\n";
            if ($host) { $ip = gethostbyname($host); echo "[diag] gethostbyname={$ip}\n"; }
            exit(0);
        '
    }
    # Initial aggressive attempts
    for i in $(seq 1 60); do
        diagnose_db || true
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
        diagnose_db || true
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

echo "Migrations/seeders will run in background when DB becomes available."
echo "Clearing caches will be performed after migrations in background job."

echo "Clearing config cache to ensure runtime env vars are used"
php artisan config:clear || true
php artisan cache:clear || true

# Ensure Laravel storage/logs and cache have correct ownership/permissions
echo "Fixing permissions on storage and bootstrap/cache"
mkdir -p /var/www/html/storage/logs /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true
# Ensure laravel.log exists and is writable
touch /var/www/html/storage/logs/laravel.log || true
chown www-data:www-data /var/www/html/storage/logs/laravel.log || true
chmod 664 /var/www/html/storage/logs/laravel.log || true

echo "=== Starting Apache ==="
exec apache2-foreground