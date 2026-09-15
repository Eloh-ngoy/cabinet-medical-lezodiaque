<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Early runtime: if DB host is not resolvable, force file drivers to avoid
// bootstrapping session/cache stores that require DB connectivity.
try {
    $raw = getenv('DATABASE_URL') ?: getenv('DB_URL') ?: null;
    $dbHost = null;
    if ($raw) {
        $parts = @parse_url($raw);
        if ($parts !== false && isset($parts['host'])) {
            $dbHost = $parts['host'];
        }
    }
    if (!$dbHost) {
        $dbHost = getenv('DB_HOST') ?: getenv('PGHOST') ?: null;
    }
    if ($dbHost) {
        $resolved = @gethostbyname($dbHost);
        if (!$resolved || $resolved === $dbHost) {
            putenv('SESSION_DRIVER=file');
            putenv('CACHE_DRIVER=file');
            error_log("[index] DB host '{$dbHost}' not resolvable; forcing SESSION_DRIVER=file and CACHE_DRIVER=file\n");
            // Remove cached config so Laravel reads env at runtime
            $configCache = __DIR__ . '/../bootstrap/cache/config.php';
            if (file_exists($configCache)) {
                @unlink($configCache);
                error_log("[index] Removed config cache at {$configCache}\n");
            }
        }
    } else {
        putenv('SESSION_DRIVER=file');
        putenv('CACHE_DRIVER=file');
        error_log('[index] No DB host env found; forcing SESSION_DRIVER=file and CACHE_DRIVER=file\n');
        $configCache = __DIR__ . '/../bootstrap/cache/config.php';
        if (file_exists($configCache)) {
            @unlink($configCache);
            error_log("[index] Removed config cache at {$configCache}\n");
        }
    }
} catch (Throwable $e) {
    putenv('SESSION_DRIVER=file');
    putenv('CACHE_DRIVER=file');
    error_log('[index] Exception checking DB host; forcing file drivers\n');
}

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
