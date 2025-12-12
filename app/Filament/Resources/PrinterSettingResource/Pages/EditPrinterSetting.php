<?php

namespace App\Filament\Resources\PrinterSettingResource\Pages;

use App\Filament\Resources\PrinterSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrinterSetting extends EditRecord
{
    protected static string $resource = PrinterSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Printer settings updated successfully';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}