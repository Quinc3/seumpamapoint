<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function created(Order $order): void
    {
        Log::info("🎯 ORDER OBSERVER CREATED: " . $order->id);
        
        // Simpan order details dari session atau temporary storage
        $this->saveOrderDetails($order);
    }

    protected function saveOrderDetails(Order $order): void
    {
        // Ambil order_items dari session (kita akan simpan di beforeCreate)
        $orderItems = session()->get('current_order_items', []);
        
        Log::info("💾 OBSERVER SAVING ORDER DETAILS - Order: " . $order->id, [
            'order_items_count' => count($orderItems),
            'order_items' => $orderItems
        ]);

        if (!empty($orderItems)) {
            foreach ($orderItems as $item) {
                if (!empty($item['product_id']) && !empty($item['qty'])) {
                    Log::info("➕ OBSERVER Creating order detail:", $item);

                    // Create order detail
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // DECREMENT STOCK
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        Log::info("📦 OBSERVER BEFORE DECREMENT - Product: " . $product->name . ", Stock: " . $product->stock . ", Qty: " . $item['qty']);
                        
                        $product->decrement('stock', $item['qty']);
                        $product->refresh();
                        
                        Log::info("📦 OBSERVER AFTER DECREMENT - Product: " . $product->name . ", Stock: " . $product->stock);
                        
                        if ($product->stock <= 0) {
                            $product->update(['in_stock' => false]);
                            Log::info("🔄 OBSERVER Product marked as out of stock: " . $product->name);
                        }
                    }
                }
            }
            
            // Clear session data
            session()->forget('current_order_items');
        }

        Log::info("✅ OBSERVER Order details completed for order: " . $order->id);
    }
}