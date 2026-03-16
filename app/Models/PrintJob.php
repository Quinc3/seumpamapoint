<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintJob extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'attempts',
        'last_attempt_at',
        'error_message',
        'cash_received',
        'cash_change',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
