<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Services\ThermalPrintService;
use App\Models\PrinterSetting;
use Illuminate\Support\Facades\Log;

class AutoPrintInvoice
{
    protected $printService;

    public function __construct(ThermalPrintService $printService)
    {
        $this->printService = $printService;
    }

    public function handle(OrderPaid $event)
    {
        Log::info("🎯 AutoPrintInvoice Listener TRIGGERED for order: " . $event->order->id);
        
        $printerSettings = PrinterSetting::getSettings();
        
        Log::info("Printer settings check:", [
            'auto_print' => $printerSettings->auto_print,
            'printer_name' => $printerSettings->printer_name
        ]);

        if ($printerSettings->auto_print) {
            Log::info("🖨️ AUTO-PRINT ENABLED - Printing order: " . $event->order->id);
            $this->printService->printInvoice($event->order);
        } else {
            Log::info("❌ AUTO-PRINT DISABLED - Skipping order: " . $event->order->id);
        }
        
        Log::info("✅ AutoPrintInvoice Listener COMPLETED");
    }
}