<?php

define('LARAVEL_START', microtime(true));

$autoloadPath = __DIR__.'/../vendor/autoload.php';
$autoloadRealPath = __DIR__.'/../vendor/composer/autoload_real.php';
$installedJsonPath = __DIR__.'/../vendor/composer/installed.json';
$hasPlaceholderVendor = file_exists($installedJsonPath)
    && str_contains((string) file_get_contents($installedJsonPath), 'Placeholder only');

if (! file_exists($autoloadPath) || ! file_exists($autoloadRealPath) || $hasPlaceholderVendor) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Application dependencies are incomplete.\n";
    echo "Expected files:\n";
    echo "- {$autoloadPath}\n";
    echo "- {$autoloadRealPath}\n\n";
    echo "For offline deployment, copy a complete vendor directory generated via:\n";
    echo "composer install --no-dev --optimize-autoloader\n";
    exit(1);
}

require $autoloadPath;

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
