<?php

define('LARAVEL_START', microtime(true));

// Setup temporary writable directories for Vercel's read-only serverless filesystem
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    $storageDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache',
        '/tmp/storage/logs',
        '/tmp/bootstrap/cache',
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    // Copy SQLite database to /tmp if exists
    $sqliteSource = __DIR__ . '/../database/database.sqlite';
    $sqliteTarget = '/tmp/database.sqlite';
    if (!file_exists($sqliteTarget) && file_exists($sqliteSource)) {
        @copy($sqliteSource, $sqliteTarget);
    }
    if (file_exists($sqliteTarget) && (!isset($_ENV['DB_CONNECTION']) || $_ENV['DB_CONNECTION'] === 'sqlite')) {
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = $sqliteTarget;
        putenv("DB_CONNECTION=sqlite");
        putenv("DB_DATABASE={$sqliteTarget}");
    }

    $_ENV['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
    $_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_ENV['LOG_CHANNEL'] = 'stderr';
}

require __DIR__ . '/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}

$app->handleRequest(\Illuminate\Http\Request::capture());

