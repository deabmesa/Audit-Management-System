<?php

$basePath = dirname(__DIR__);
$compiledPath = $basePath.'/storage/framework/views';
$compiledOverride = $_ENV['VIEW_COMPILED_PATH'] ?? $_SERVER['VIEW_COMPILED_PATH'] ?? null;

return [
    'paths' => [$basePath.'/resources/views'],

    'compiled' => $compiledOverride ?: (is_dir($compiledPath) ? realpath($compiledPath) : $compiledPath),
];
