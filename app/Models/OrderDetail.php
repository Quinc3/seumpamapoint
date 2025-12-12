<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
        'subtotal',
    ];

    // Gunakan float instead of decimal
    protected $casts = [
        'price' => 'float',
        'subtotal' => 'float',
        'qty' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::saving(function ($orderDetail) {
            // Calculate subtotal automatically dengan type conversion
            $subtotal = (float) $orderDetail->price * (int) $orderDetail->qty;
            $orderDetail->subtotal = $subtotal;
        });

        static::saved(function ($orderDetail) {
            if ($orderDetail->order) {
                $orderDetail->order->calculateTotals();
                $orderDetail->order->save();
            }
        });

        static::deleted(function ($orderDetail) {
            if ($orderDetail->order) {
                $orderDetail->order->calculateTotals();
                $orderDetail->order->save();
            }
        });
    }

    // Accessor untuk format display
    public function getSubtotalFormattedAttribute()
    {
        return 'IDR ' . number_format($this->subtotal, 0, ',', '.');
    }
}