<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Order;

$order = Order::with('items.product','user')->find(92);
if (! $order) {
    echo "Order not found\n";
    exit(1);
}

echo "Order ID: {$order->id}\n";
echo "Total payment: {$order->total_payment}\n";
echo "Cash received: " . ($order->cash_received ?? 'NULL') . "\n";
echo "Cash change: " . ($order->cash_change ?? 'NULL') . "\n";
echo "Payment status: {$order->payment_status}\n";

foreach ($order->items as $it) {
    echo "- {$it->product->name} x{$it->qty} subtotal: {$it->subtotal}\n";
}
