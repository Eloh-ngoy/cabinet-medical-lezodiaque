#!/bin/bash
set -e

url=${DATABASE_URL:-$DB_URL}
if [ -z "$url" ]; then
  echo "No DATABASE_URL or DB_URL set in environment."
  exit 1
fi

echo "DATABASE_URL=$url"

php -r '
$url = getenv("DATABASE_URL") ?: getenv("DB_URL");
$parts = parse_url($url);
if ($parts === false) { echo "parse_url failed\n"; exit(2); }
$host = $parts["host"] ?? "";
$port = $parts["port"] ?? 5432;
$user = $parts["user"] ?? null;
$pass = $parts["pass"] ?? null;
$dbname = isset($parts["path"]) ? ltrim($parts["path"], "/") : null;

echo "Host: $host\nPort: $port\nDB: $dbname\nUser: " . ($user ?? '(null)') . "\n";

$records = dns_get_record($host, DNS_A + DNS_AAAA);
if ($records === false || count($records) === 0) {
    echo "DNS lookup returned no A/AAAA records for $host\n";
} else {
    echo "DNS records:\n";
    foreach ($records as $r) {
        echo json_encode($r) . "\n";
    }
}

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5];
    $pdo = new PDO($dsn, $user, $pass, $opts);
    echo "PDO connect OK\n";
} catch (Exception $e) {
    echo "PDO connect failed: " . $e->getMessage() . "\n";
    exit(3);
}
'
