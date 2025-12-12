<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Product;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $orderItems = $data['order_items'] ?? [];

        if (empty($orderItems)) {
            Notification::make()
                ->title('No products added')
                ->body('Please add at least one product to create order.')
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

    protected function afterCreate(): void
    {
        // Create order details
        $orderItems = $this->orderItems ?? [];

        \Log::info("📦 CREATE ORDER - Saving order details:", [
            'order_id' => $this->record->id,
            'items_count' => count($orderItems),
            'items' => $orderItems
        ]);

        foreach ($orderItems as $item) {
            // Create order detail
            $this->record->orderDetails()->create([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            // ⬇️⬇️ DECREMENT PRODUCT STOCK ⬇️⬇️
            $product = Product::with('ingredients')->find($item['product_id']);
            if ($product) {
                \Log::info("📦 DECREMENTING STOCK - Product: " . $product->name .
                    ", Stock Before: " . $product->stock .
                    ", Qty: " . $item['qty']);

                $product->decrement('stock', $item['qty']);
                $product->refresh();

                \Log::info("📦 STOCK UPDATED - Product: " . $product->name .
                    ", Stock After: " . $product->stock);

                // Update in_stock status jika perlu
                if ($product->stock <= 0) {
                    $product->update(['in_stock' => false]);
                    \Log::info("🔄 Product marked as out of stock: " . $product->name);
                }

                // ⬇️⬇️ TAMBAHKAN PENGURANGAN STOK BAHAN BAKU DI SINI ⬇️⬇️
                if ($product->usesIngredients()) {
                    \Log::info("🥛 CONSUMING INGREDIENTS for product: " . $product->name . 
                        ", Quantity: " . $item['qty']);
                    
                    foreach ($product->ingredients as $ingredient) {
                        $quantityNeeded = $ingredient->pivot->quantity * $item['qty'];
                        
                        \Log::info("📊 Ingredient consumption:", [
                            'ingredient' => $ingredient->name,
                            'unit' => $ingredient->unit,
                            'needed_per_product' => $ingredient->pivot->quantity,
                            'quantity_sold' => $item['qty'],
                            'total_needed' => $quantityNeeded,
                            'stock_before' => $ingredient->stock
                        ]);

                        // Kurangi stok bahan baku
                        $ingredient->decrement('stock', $quantityNeeded);
                        $ingredient->refresh();

                        \Log::info("✅ Ingredient stock updated:", [
                            'ingredient' => $ingredient->name,
                            'stock_after' => $ingredient->stock,
                            'consumed' => $quantityNeeded
                        ]);

                        // Cek jika stok bahan baku rendah
                        if ($ingredient->stock <= $ingredient->min_stock) {
                            \Log::warning("⚠️ LOW STOCK ALERT - Ingredient: " . $ingredient->name . 
                                ", Current Stock: " . $ingredient->stock . $ingredient->unit . 
                                ", Min Stock: " . $ingredient->min_stock . $ingredient->unit);
                            
                            // Bisa tambahkan notification ke admin di sini
                            Notification::make()
                                ->title('Low Stock Alert')
                                ->body("Ingredient {$ingredient->name} is running low. Current: {$ingredient->stock}{$ingredient->unit}")
                                ->warning()
                                ->sendToDatabase(\App\Models\User::role('admin')->get());
                        }

                        // Cek jika stok habis
                        if ($ingredient->stock <= 0) {
                            \Log::error("❌ OUT OF STOCK - Ingredient: " . $ingredient->name . " is out of stock!");
                            
                            Notification::make()
                                ->title('Out of Stock Alert')
                                ->body("Ingredient {$ingredient->name} is out of stock!")
                                ->danger()
                                ->sendToDatabase(\App\Models\User::role('admin')->get());
                        }
                    }
                } else {
                    \Log::info("ℹ️ Product does not use ingredients: " . $product->name);
                }
            }
        }

        // QUICK FIX: Direct print call
        if ($this->record->payment_status === 'paid') {
            \Log::info("🖨️ CREATE ORDER - Direct print call for order: " . $this->record->id);
            $printService = new \App\Services\ThermalPrintService();
            $printService->printInvoice($this->record);
        }
    }

    protected $orderItems = [];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}