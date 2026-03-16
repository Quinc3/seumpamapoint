<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// change order id as needed
$request = Illuminate\Http\Request::create('/print/invoice/92', 'GET');
$response = $kernel->handle($request);

echo "STATUS: " . $response->getStatusCode() . PHP_EOL;
echo "BODY:\n" . (string)$response->getContent() . PHP_EOL;

$kernel->terminate($request, $response);
