<?php

define('LARAVEL_START', microtime(true));

$autoloadPath = __DIR__.'/../vendor/autoload.php';

if (! file_exists($autoloadPath)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Application dependencies are missing.\n";
    echo "Expected file: {$autoloadPath}\n\n";
    echo "For offline deployment, copy a prebuilt vendor directory generated via:\n";
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
