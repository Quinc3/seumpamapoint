<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\PrintJob;

class PrintStatusWidget extends Widget
{
    protected static ?string $heading = 'Printer Status';

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.print-status-widget';

    protected function getViewData(): array
    {
        $lastJob = PrintJob::orderBy('created_at', 'desc')->first();

        return [
            'lastJob' => $lastJob,
        ];
    }
}
