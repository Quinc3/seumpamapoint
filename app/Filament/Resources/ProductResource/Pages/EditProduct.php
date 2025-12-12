<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing ingredients
        $product = Product::with('ingredients')->find($this->record->id);
        $data['ingredient_items'] = [];

        foreach ($product->ingredients as $ingredient) {
            $data['ingredient_items'][] = [
                'ingredient_id' => $ingredient->id,
                'quantity' => $ingredient->pivot->quantity
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan data ingredients terpisah
        $this->ingredientItems = $data['ingredient_items'] ?? [];
        unset($data['ingredient_items']);

        return $data;
    }

    protected function afterSave(): void
    {
        if (isset($this->ingredientItems)) {
            $ingredientsToSync = [];

            foreach ($this->ingredientItems as $item) {
                if (!empty($item['ingredient_id']) && !empty($item['quantity'])) {
                    $ingredientsToSync[$item['ingredient_id']] = ['quantity' => $item['quantity']];
                }
            }

            $this->record->ingredients()->sync($ingredientsToSync);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}