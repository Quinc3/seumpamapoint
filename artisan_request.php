<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/print/pending', 'GET');
$response = $kernel->handle($request);

echo "STATUS: " . $response->getStatusCode() . PHP_EOL;
echo "HEADERS:\n";
foreach ($response->headers->allPreserveCase() as $k => $v) {
    echo $k . ': ' . implode(', ', $v) . PHP_EOL;
}

echo "\nBODY:\n" . (string)$response->getContent() . PHP_EOL;

$kernel->terminate($request, $response);
