<?php

namespace App\Filament\Resources\IngredientResource\Pages;

use App\Models\Ingredient;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\IngredientResource;

class ViewIngredient extends ViewRecord
{
    protected static string $resource = IngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}