<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'stock',
        'min_stock',
        'cost_per_unit',
        'is_active'
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relasi ke products (many-to-many)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Helper methods
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success'
        };
    }

    // Method untuk mengurangi stok
    public function reduceStock(float $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    // Method untuk menambah stok
    public function addStock(float $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    // Cek apakah stok cukup
    public function hasEnoughStock(float $neededQuantity): bool
    {
        return $this->stock >= $neededQuantity;
    }
}