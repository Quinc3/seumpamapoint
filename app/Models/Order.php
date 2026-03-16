<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OrderDetail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    /**
     * RELASI ORDER → ITEMS
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * HITUNG TOTAL
     */
    public function calculateTotals(): void
    {
        $this->loadMissing('items');

        $subtotal = $this->items->sum(
            fn($item) =>
            (float) $item->price * (int) $item->qty
        );

        $this->total_price = $subtotal;
        $this->discount_amount = $subtotal * ($this->discount / 100);
        $this->total_payment = max(0, $subtotal - $this->discount_amount);
    }

    /**
     * FORMAT DISPLAY
     */
    public function getTotalPriceFormattedAttribute(): string
    {
        return 'IDR ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getDiscountAmountFormattedAttribute(): string
    {
        return 'IDR ' . number_format($this->discount_amount, 0, ',', '.');
    }

    public function getTotalPaymentFormattedAttribute(): string
    {
        return 'IDR ' . number_format($this->total_payment, 0, ',', '.');
    }

    // User relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
