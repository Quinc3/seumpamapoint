<?php

namespace App\Filament\Resources\InvoiceSettingResource\Pages;

use App\Filament\Resources\InvoiceSettingResource;
use App\Models\InvoiceSetting;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoiceSettings extends ManageRecords
{
    protected static string $resource = InvoiceSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data) {
                    // Delete existing records to ensure only one exists
                    InvoiceSetting::query()->delete();
                    return InvoiceSetting::create($data);
                })
                ->label('Create Settings')
                ->visible(fn() => InvoiceSetting::count() === 0),
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No invoice settings found';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create your invoice settings to customize how your invoices look.';
    }
}