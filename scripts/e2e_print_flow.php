<?php

require __DIR__ . '/../vendor/autoload.php';
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Events\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Prepare data
$user = User::first();
if (! $user) {
    $user = User::create([
        'name' => 'E2E Test',
        'email' => 'e2e+test@example.test',
        'password' => bcrypt('password'),
    ]);
}

$product = Product::where('stock', '>', 0)->first();
if (! $product) {
    echo "No product with stock available\n";
    exit(1);
}

// Create paid order
$order = Order::create([
    'user_id' => $user->id,
    'customer_name' => 'E2E Customer',
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

// Fire event to enqueue print job
event(new OrderPaid($order));

// Poll pending job via controller directly
$controller = app(\App\Http\Controllers\PrintJobController::class);
$service = app(\App\Services\ThermalPrintService::class);
$pendingResponse = $controller->pending($service);

echo "PENDING RESPONSE STATUS: " . $pendingResponse->getStatusCode() . PHP_EOL;
echo "PENDING RESPONSE BODY:\n" . (string)$pendingResponse->getContent() . PHP_EOL;

// Decode and mark complete
$payload = json_decode((string)$pendingResponse->getContent(), true);
$jobId = $payload['job_id'] ?? null;
if (! $jobId) {
    echo "No job id returned, aborting complete step.\n";
    exit(1);
}

$request = Request::create('/admin/print/' . $jobId . '/complete', 'POST', ['status' => 'done']);
$completeResponse = $controller->complete($request, $jobId);

echo "COMPLETE RESPONSE: " . (string)$completeResponse->getContent() . PHP_EOL;

// Dump DB row for verification
$job = DB::table('print_jobs')->where('id', $jobId)->first();
echo "PRINT JOB ROW:\n";
print_r($job);

$orderFresh = Order::with('items')->find($order->id);
echo "ORDER DB:\n";
print_r([
    'id' => $orderFresh->id,
    'payment_status' => $orderFresh->payment_status,
    'cash_received' => $orderFresh->cash_received,
    'cash_change' => $orderFresh->cash_change,
]);

echo "E2E flow complete.\n";
