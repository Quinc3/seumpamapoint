<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Services\OrderService;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected array $orderItems = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // WAJIB load relasi items
        $this->record->load('items');

        $data['order_items'] = $this->record->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->orderItems = $data['order_items'] ?? [];

        if (empty($this->orderItems)) {
            Notification::make()
                ->title('No products added')
                ->body('Please add at least one product.')
                ->danger()
                ->send();

            $this->halt();
        }

        $subtotal = collect($this->orderItems)->sum('subtotal');
        $discount = (float) ($data['discount'] ?? 0);
        $discountAmount = $subtotal * $discount / 100;

        $data['total_price'] = $subtotal;
        $data['discount_amount'] = $discountAmount;
        $data['total_payment'] = max(0, $subtotal - $discountAmount);

        return $data;
    }

    protected function afterSave(): void
    {
        $service = app(OrderService::class);

        // JIKA DIBATALKAN
        if (
            $this->record->wasChanged('status') &&
            $this->record->getOriginal('status') !== 'cancelled' &&
            $this->record->status === 'cancelled'
        ) {
            $service->cancel($this->record);
            return;
        }

        // UPDATE ITEM
        $service->update($this->record, $this->orderItems);

        // REFRESH RELASI
        $this->record->load('items.product');

        // PRINT JIKA PAID
        if (
            $this->record->wasChanged('payment_status') &&
            $this->record->payment_status === 'paid'
        ) {
            $this->dispatch('order-paid', [
                'order' => [
                    'id' => $this->record->id,
                    'total' => $this->record->total_payment,
                    'items' => $this->record->items->map(fn($item) => [
                        'name' => $item->product->name,
                        'qty' => $item->qty,
                        'price' => $item->price,
                    ])->toArray(),
                ],
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
