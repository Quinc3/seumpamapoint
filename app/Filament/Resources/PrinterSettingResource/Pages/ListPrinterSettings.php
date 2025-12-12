<?php

namespace App\Filament\Resources\PrinterSettingResource\Pages;

use App\Filament\Resources\PrinterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrinterSettings extends ListRecords
{
    protected static string $resource = PrinterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Printer Settings'),
        ];
    }
}