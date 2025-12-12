<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Events\OrderPaid;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'total_price',
        'discount',
        'discount_amount',
        'total_payment',
        'status',
        'payment_status',
        'payment_method',
        'cash_received',
        'cash_change',
    ];

    protected $casts = [
        'total_price' => 'float',
        'discount' => 'float',
        'discount_amount' => 'float',
        'total_payment' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderdetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    protected static function booted()
    {
        static::updated(function ($order) {
            // Cek jika payment_status berubah menjadi 'paid'
            if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
                \Log::info('Order payment status changed to paid, triggering auto-print for order: ' . $order->id);
                event(new OrderPaid($order));
            }
        });
    }

    // Helper method untuk cek perubahan status
    public function wasRecentlyPaid(): bool
    {
        return $this->isDirty('payment_status') && $this->payment_status === 'paid';
    }


    // Helper method to calculate totals - FIXED TYPE CONVERSION
    public function calculateTotals(): void
    {
        $subtotal = $this->orderDetails->sum(function ($detail) {
            return (float) $detail->price * (int) $detail->qty;
        });

        // Explicit type conversion
        $this->total_price = (float) $subtotal;
        $this->discount_amount = (float) ($subtotal * ((float) $this->discount / 100));
        $this->total_payment = (float) ($subtotal - $this->discount_amount);
    }

    // Accessor untuk format display
    public function getTotalPriceFormattedAttribute()
    {
        return 'IDR ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getDiscountAmountFormattedAttribute()
    {
        return 'IDR ' . number_format($this->discount_amount, 0, ',', '.');
    }

    public function getTotalPaymentFormattedAttribute()
    {
        return 'IDR ' . number_format($this->total_payment, 0, ',', '.');
    }
}