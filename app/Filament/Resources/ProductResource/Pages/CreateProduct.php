<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan data ingredients terpisah
        $this->ingredientItems = $data['ingredient_items'] ?? [];
        unset($data['ingredient_items']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Sync ingredients setelah product dibuat
        if (isset($this->ingredientItems) && !empty($this->ingredientItems)) {
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