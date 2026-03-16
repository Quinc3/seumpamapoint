<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\PrinterSetting;

class PrintQueueWidget extends Widget
{
    protected static ?string $heading = 'Print Queue (Auto-Print)';

    protected int|string|array $columnSpan = 1;

    protected function getViewData(): array
    {
        $settings = PrinterSetting::getSettings();
        return [
            'printerName' => $settings->printer_name ?? null,
        ];
    }

    // The widget view is simple; we rely on JS polling endpoint /admin/print/pending
    protected static string $view = 'filament.widgets.print-queue-widget';
}
