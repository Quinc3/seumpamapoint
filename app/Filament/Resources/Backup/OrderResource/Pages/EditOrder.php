<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Product;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $orderItems = $data['order_items'] ?? [];

        if (empty($orderItems)) {
            Notification::make()
                ->title('No products added')
                ->body('Please add at least one product to update order.')
                ->danger()
                ->send();
            $this->halt();
        }

        // Calculate totals
        $subtotal = collect($orderItems)->sum('subtotal');
        $discount = floatval($data['discount'] ?? 0);
        $discountAmount = $subtotal * $discount / 100;
        $totalPayment = max(0, $subtotal - $discountAmount);

        $data['total_price'] = $subtotal;
        $data['discount_amount'] = $discountAmount;
        $data['total_payment'] = $totalPayment;

        // Store order items for later use
        $this->orderItems = $orderItems;

        return $data;
    }

    protected function afterSave(): void
    {
        \Log::info("🔍 AFTER SAVE - Order Status: " . $this->record->status);
        \Log::info("🔍 AFTER SAVE - Was Status Changed: " . ($this->record->wasChanged('status') ? 'YES' : 'NO'));

        // DEBUG: Cek apa yang terjadi
        \Log::info("🔍 DEBUG ORDER DATA:", [
            'order_id' => $this->record->id,
            'current_status' => $this->record->status,
            'original_status' => $this->record->getOriginal('status'),
            'was_changed' => $this->record->wasChanged('status'),
            'order_details_count' => $this->record->orderDetails->count()
        ]);

        // Handle order cancellation - PASTIKAN INI DIPANGGIL
        if ($this->record->wasChanged('status') && $this->record->status === 'cancelled') {
            \Log::info("🎯 STATUS CHANGED TO CANCELLED - Processing cancellation...");
            $this->handleOrderCancellation();
            return;
        }

        \Log::info("🔄 PROCESSING NORMAL ORDER UPDATE...");
        $this->handleNormalOrderUpdate();
    }

    protected function handleNormalOrderUpdate(): void
    {
        \Log::info("🔄 PROCESSING NORMAL ORDER UPDATE");

        // Simpan order details lama sebelum dihapus
        $oldOrderDetails = $this->record->orderDetails()->with('product.ingredients')->get();

        \Log::info("📦 OLD ORDER DETAILS:", $oldOrderDetails->pluck('product_id', 'qty')->toArray());

        // 1. KEMBALIKAN STOK DARI ORDER LAMA
        foreach ($oldOrderDetails as $oldDetail) {
            if ($oldDetail->product) {
                $this->returnStock($oldDetail->product, $oldDetail->qty, "old order");
            }
        }

        // 2. HAPUS ORDER DETAILS LAMA
        $this->record->orderDetails()->delete();

        // 3. BUAT ORDER DETAILS BARU DAN KURANGI STOK
        $orderItems = $this->orderItems ?? [];

        foreach ($orderItems as $item) {
            // Create order detail
            $this->record->orderDetails()->create([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            // Kurangi stok untuk order baru
            $product = Product::with('ingredients')->find($item['product_id']);
            if ($product) {
                $this->consumeStock($product, $item['qty'], "new order");
            }
        }

        \Log::info("✅ NORMAL ORDER UPDATE COMPLETED");
    }

    protected function handleOrderCancellation(): void
    {
        \Log::info("🎯 STARTING ORDER CANCELLATION PROCESS");

        $orderDetails = $this->record->orderDetails()->with('product.ingredients')->get();

        \Log::info("📦 ORDER DETAILS TO CANCEL:", [
            'count' => $orderDetails->count(),
            'details' => $orderDetails->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->product->name ?? 'N/A',
                    'qty' => $detail->qty
                ];
            })->toArray()
        ]);

        if ($orderDetails->isEmpty()) {
            \Log::warning("⚠️ NO ORDER DETAILS FOUND FOR CANCELLATION");
            return;
        }

        $returnedProducts = [];

        foreach ($orderDetails as $orderDetail) {
            $product = $orderDetail->product;

            if ($product) {
                \Log::info("🔄 PROCESSING CANCELLATION FOR PRODUCT: " . $product->name . " (Qty: " . $orderDetail->qty . ")");

                // Simpan stok sebelum pengembalian
                $productStockBefore = $product->stock;
                $ingredientsBefore = [];

                if ($product->usesIngredients()) {
                    foreach ($product->ingredients as $ingredient) {
                        $ingredientsBefore[$ingredient->name] = $ingredient->stock;
                    }
                }

                // KEMBALIKAN STOK
                $this->returnStock($product, $orderDetail->qty, "cancellation");

                // Simpan stok setelah pengembalian
                $product->refresh();
                $productStockAfter = $product->stock;

                $returnedProducts[] = [
                    'product' => $product->name,
                    'quantity' => $orderDetail->qty,
                    'stock_before' => $productStockBefore,
                    'stock_after' => $productStockAfter,
                    'ingredients_before' => $ingredientsBefore
                ];

                \Log::info("✅ STOCK RETURNED - {$product->name}: {$productStockBefore} → {$productStockAfter}");
            } else {
                \Log::error("❌ PRODUCT NOT FOUND FOR ORDER DETAIL: " . $orderDetail->id);
            }
        }

        \Log::info("📊 CANCELLATION SUMMARY:", $returnedProducts);

        Notification::make()
            ->title('Order Cancelled Successfully')
            ->body('Product stocks and ingredients have been returned.')
            ->success()
            ->send();

        \Log::info("✅ ORDER CANCELLATION COMPLETED");
    }

    /**
     * SIMPLE METHOD UNTUK KEMBALIKAN STOK
     */
    protected function returnStock(Product $product, int $quantity, string $reason): void
    {
        \Log::info("🔄 RETURN STOCK - Product: {$product->name}, Qty: {$quantity}, Reason: {$reason}");

        // 1. KEMBALIKAN STOK PRODUK
        $stockBefore = $product->stock;
        $product->stock += $quantity; // Langsung tambah
        $product->save();

        \Log::info("📦 PRODUCT STOCK - {$product->name}: {$stockBefore} + {$quantity} = {$product->stock}");

        // Update in_stock status
        if ($product->stock > 0 && !$product->in_stock) {
            $product->update(['in_stock' => true]);
            \Log::info("🔄 Product marked as in stock: " . $product->name);
        }

        // 2. KEMBALIKAN STOK BAHAN BAKU
        if ($product->usesIngredients()) {
            $product->load('ingredients'); // Pastikan ingredients ter-load
            \Log::info("🥛 RETURNING INGREDIENTS for {$product->name}");

            foreach ($product->ingredients as $ingredient) {
                $quantityToReturn = $ingredient->pivot->quantity * $quantity;
                $ingredientStockBefore = $ingredient->stock;

                $ingredient->stock += $quantityToReturn; // Langsung tambah
                $ingredient->save();

                \Log::info("✅ INGREDIENT RETURNED - {$ingredient->name}: {$ingredientStockBefore} + {$quantityToReturn} = {$ingredient->stock}");
            }
        }
    }

    /**
     * SIMPLE METHOD UNTUK KURANGI STOK
     */
    protected function consumeStock(Product $product, int $quantity, string $reason): void
    {
        \Log::info("📦 CONSUME STOCK - Product: {$product->name}, Qty: {$quantity}, Reason: {$reason}");

        // 1. KURANGI STOK PRODUK
        $stockBefore = $product->stock;
        $product->stock = max(0, $product->stock - $quantity); // Pastikan tidak minus
        $product->save();

        \Log::info("📦 PRODUCT STOCK - {$product->name}: {$stockBefore} - {$quantity} = {$product->stock}");

        // Update in_stock status
        if ($product->stock <= 0) {
            $product->update(['in_stock' => false]);
            \Log::info("🔄 Product marked as out of stock: " . $product->name);
        }

        // 2. KURANGI STOK BAHAN BAKU
        if ($product->usesIngredients()) {
            $product->load('ingredients');
            \Log::info("🥛 CONSUMING INGREDIENTS for {$product->name}");

            foreach ($product->ingredients as $ingredient) {
                $quantityNeeded = $ingredient->pivot->quantity * $quantity;
                $ingredientStockBefore = $ingredient->stock;

                $ingredient->stock = max(0, $ingredient->stock - $quantityNeeded); // Pastikan tidak minus
                $ingredient->save();

                \Log::info("✅ INGREDIENT CONSUMED - {$ingredient->name}: {$ingredientStockBefore} - {$quantityNeeded} = {$ingredient->stock}");
            }
        }
    }

    protected $orderItems = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}