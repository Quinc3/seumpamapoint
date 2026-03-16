<?php

require __DIR__ . '/../vendor/autoload.php';
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Bootstrap the console kernel so facades and Eloquent are ready
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Events\OrderPaid;

// Create a simple order with one existing product
// Ensure there is a user for the order (respect foreign key)
$user = User::first();
if (! $user) {
    $user = User::create([
        'name' => 'Test Admin',
        'email' => 'test+autoprint@example.test',
        'password' => bcrypt('password'),
    ]);
}

$product = Product::where('stock', '>', 0)->first();
if (! $product) {
    echo "No product with stock available\n";
    exit(1);
}

$order = Order::create([
    'user_id' => $user->id,
    'customer_name' => 'Test Customer',
    'total_price' => $product->price,
    'discount' => 0,
    'discount_amount' => 0,
    'total_payment' => $product->price,
    'status' => 'new',
    'payment_status' => 'paid',
    'payment_method' => 'cash',
    'cash_received' => $product->price + 10000,
    'cash_change' => 10000,
]);

OrderDetail::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'qty' => 1,
    'price' => $product->price,
    'subtotal' => $product->price,
]);

// Fire OrderPaid to enqueue print job
event(new OrderPaid($order));

// Directly call the controller pending method to avoid auth redirects
$controller = app(\App\Http\Controllers\PrintJobController::class);
$service = app(\App\Services\ThermalPrintService::class);
$response = $controller->pending($service);

echo "DIRECT CONTROLLER OUTPUT:\n" . (string)$response->getContent() . PHP_EOL;
