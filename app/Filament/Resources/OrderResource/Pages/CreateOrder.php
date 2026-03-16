<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $orderItems = $this->form->getState()['order_items'] ?? [];

        if (empty($orderItems)) {
            Notification::make()
                ->title('No products added')
                ->danger()
                ->send();

            $this->halt();
        }

        // HITUNG TOTAL
        $subtotal = collect($orderItems)->sum('subtotal');
        $discount = (float) ($data['discount'] ?? 0);
        $discountAmount = $subtotal * $discount / 100;
        $totalPayment = max(0, $subtotal - $discountAmount);

        $data['total_price'] = $subtotal;
        $data['discount_amount'] = $discountAmount;
        $data['total_payment'] = $totalPayment;

        if (! Auth::check()) {
            Notification::make()
                ->title('You must be logged in to create orders')
                ->danger()
                ->send();

            $this->halt();
        }

        $data['user_id'] = Auth::id();

        $model = static::getModel()::create($data);

        // Immediately create order items and consume stock while we still
        // have the form state available (`$orderItems`). Placing this
        // here prevents `afterCreate()` from seeing an emptied form state.
        app(OrderService::class)->create($model, $orderItems);

        return $model;
    }

    protected function afterCreate(): void
    {
        // Intentionally left empty: order items are handled in
        // handleRecordCreation to ensure form state is available.
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
