<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Runtime DB availability check — if the DB host cannot be resolved
// force session/cache drivers to `file` to avoid early database calls
// (prevents request-time exceptions when DATABASE_URL is temporarily invalid).
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
            error_log("[bootstrap] DB host '{$dbHost}' not resolvable; forcing SESSION_DRIVER=file and CACHE_DRIVER=file\n");
        }
    } else {
        // No DB host configured — safer to use file drivers
        putenv('SESSION_DRIVER=file');
        putenv('CACHE_DRIVER=file');
        error_log("[bootstrap] No DB host env found; forcing SESSION_DRIVER=file and CACHE_DRIVER=file\n");
    }
} catch (Throwable $e) {
    // Non-fatal; continue boot, but ensure file drivers as fallback
    putenv('SESSION_DRIVER=file');
    putenv('CACHE_DRIVER=file');
    error_log('[bootstrap] Exception while checking DB host; forcing file drivers\n');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->appendToGroup('web', \App\Http\Middleware\ForcePasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();