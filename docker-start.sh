#!/bin/bash
set -e

echo "=== MediNexus Starting ==="
echo "APP_KEY length: ${#APP_KEY}"

# Wait for PostgreSQL (parse DATABASE_URL/DB_URL into a PDO DSN)
if [ -n "$DB_URL" ] || [ -n "$DATABASE_URL" ]; then
    DB_CONNECTION_URL="${DB_URL:-$DATABASE_URL}"
    echo "Waiting for PostgreSQL..."
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
        ' 2>/dev/null && break
        echo "  Attempt $i/60 - DB not ready yet..."
        sleep 5
    done
    # After loop, check last result
    php -r ' $url = getenv("DB_URL") ?: getenv("DATABASE_URL"); $parts=parse_url($url); $host=$parts["host"]??""; $port=$parts["port"]??5432; $user=$parts["user"]??null; $pass=$parts["pass"]??null; $path=$parts["path"]??null; $dbname=$path?ltrim($path,"/"):null; $dsn="pgsql:host={$host};port={$port};dbname={$dbname}"; try{ new PDO($dsn,$user,$pass); exit(0);}catch(Exception$e){ fwrite(STDERR, "DB still not ready\n"); exit(1);} ' 2>/dev/null || { echo "Database is not ready after retries."; exit 1; }
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