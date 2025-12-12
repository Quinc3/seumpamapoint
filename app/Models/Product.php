<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'cost_price',
        'stock',
        'image',
        'category_id',
        'is_active',
        'in_stock'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'cost_price' => 'float',
        'is_active' => 'boolean',
        'in_stock' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getPriceFormattedAttribute()
    {
        return 'IDR ' . number_format($this->price, 0, ',', '.');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : 'No Category';
    }

    public function usesIngredients(): bool
    {
        return $this->ingredients()->exists();
    }

    public function getIngredientCostAttribute(): float
    {
        if (!$this->usesIngredients()) {
            return 0;
        }

        return $this->ingredients->sum(function ($ingredient) {
            return $ingredient->pivot->quantity * $ingredient->cost_per_unit;
        });
    }

    public function canBeProduced(): bool
    {
        if (!$this->usesIngredients()) {
            return true;
        }

        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->stock < $ingredient->pivot->quantity) {
                return false;
            }
        }
        return true;
    }

    public function consumeIngredients(): bool
    {
        if (!$this->usesIngredients()) {
            return true;
        }

        if (!$this->canBeProduced()) {
            return false;
        }

        foreach ($this->ingredients as $ingredient) {
            $ingredient->decrement('stock', $ingredient->pivot->quantity);
        }

        return true;
    }
    public function getProductionStatusAttribute(): string
    {
        if (!$this->usesIngredients()) {
            return 'no_ingredients';
        }

        return $this->canBeProduced() ? 'can_produce' : 'cannot_produce';
    }

    public function getProductionStatusTextAttribute(): string
    {
        return match ($this->production_status) {
            'no_ingredients' => 'No Ingredients Needed',
            'can_produce' => 'Can Be Produced',
            'cannot_produce' => 'Insufficient Ingredients',
            default => 'Unknown'
        };
    }

    public function getProductionStatusColorAttribute(): string
    {
        return match ($this->production_status) {
            'no_ingredients' => 'gray',
            'can_produce' => 'success',
            'cannot_produce' => 'danger',
            default => 'gray'
        };
    }

    public function syncIngredientsFromForm(array $ingredientData): void
    {
        $ingredientsToSync = [];

        foreach ($ingredientData as $item) {
            if (!empty($item['ingredient_id']) && !empty($item['quantity'])) {
                $ingredientsToSync[$item['ingredient_id']] = ['quantity' => $item['quantity']];
            }
        }

        $this->ingredients()->sync($ingredientsToSync);
    }
}