<?php

define('LARAVEL_START', microtime(true));

// ============================================================
// Setup Vercel serverless environment (unconditional)
// /var/task is read-only; writable temp dirs must be in /tmp
// ============================================================

$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/cache/locks',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy SQLite database to writable /tmp on every cold start
$sqliteSource = __DIR__ . '/../database/database.sqlite';
$sqliteTarget = '/tmp/database.sqlite';
if (file_exists($sqliteSource) && !file_exists($sqliteTarget)) {
    @copy($sqliteSource, $sqliteTarget);
}

// Force environment variables BEFORE Laravel boots
$_ENV['DB_CONNECTION']        = 'sqlite';
$_ENV['DB_DATABASE']          = $sqliteTarget;
$_ENV['APP_CONFIG_CACHE']     = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE']     = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_SERVICES_CACHE']   = '/tmp/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE']   = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_EVENTS_CACHE']     = '/tmp/bootstrap/cache/events.php';
$_ENV['VIEW_COMPILED_PATH']   = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER']       = 'cookie';
$_ENV['LOG_CHANNEL']          = 'stderr';
$_ENV['CACHE_STORE']          = 'array';

putenv('DB_CONNECTION=sqlite');
putenv("DB_DATABASE={$sqliteTarget}");
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('CACHE_STORE=array');

// ============================================================
// Bootstrap Laravel
// ============================================================

require __DIR__ . '/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Point storage to writable /tmp path
$app->useStoragePath('/tmp/storage');

$app->handleRequest(\Illuminate\Http\Request::capture());
