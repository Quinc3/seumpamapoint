<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPaid;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * CREATE ORDER (DARI CREATE PAGE)
     */
    public function create(Order $order, array $items): void
    {
        Log::info('OrderService:create called', ['order_id' => $order->id ?? null, 'items_count' => count($items)]);
        DB::transaction(function () use ($order, $items) {

            if (empty($items)) {
                return;
            }

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                $this->consumeStock($item['product_id'], $item['qty']);
            }

            // PRINT JIKA LANGSUNG PAID
            if ($order->payment_status === 'paid') {
                event(new OrderPaid($order));
            }
        });
    }

    /**
     * UPDATE ORDER (DARI EDIT PAGE)
     */
    public function update(Order $order, array $items): void
    {
        DB::transaction(function () use ($order, $items) {

            $order->loadMissing('items.product');

            // KEMBALIKAN STOK LAMA
            foreach ($order->items as $detail) {
                $this->returnStock($detail->product_id, $detail->qty);
            }

            // HAPUS ITEM LAMA
            $order->items()->delete();

            // SIMPAN ITEM BARU
            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                $this->consumeStock($item['product_id'], $item['qty']);
            }
        });
    }

    /**
     * CANCEL ORDER
     */
    public function cancel(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $order->loadMissing('items.product');

            foreach ($order->items as $detail) {
                $this->returnStock($detail->product_id, $detail->qty);
            }

            $order->items()->delete();
        });
    }

    /**
     * CONSUME STOK PRODUK
     */
    protected function consumeStock(int $productId, int $qty): void
    {
        $product = Product::findOrFail($productId);
        $old = $product->stock;
        $product->decrement('stock', $qty);
        $product->refresh();
        Log::info("OrderService: consumed stock", ['product_id' => $productId, 'qty' => $qty, 'before' => $old, 'after' => $product->stock]);
    }

    /**
     * RETURN STOK PRODUK
     */
    protected function returnStock(int $productId, int $qty): void
    {
        $product = Product::findOrFail($productId);
        $product->increment('stock', $qty);
    }
}
