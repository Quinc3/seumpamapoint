<?php

namespace App\Filament\Resources\PrinterSettingResource\Pages;

use App\Filament\Resources\PrinterSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrinterSetting extends CreateRecord
{
    protected static string $resource = PrinterSettingResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Printer settings created successfully';
    }
}