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
            Log::info("🖨️ AUTO-PRINT ENABLED - Enqueueing print job for order: " . $event->order->id);

            // Enqueue a print job instead of printing directly. A client
            // (Filament widget or QZ Tray) will poll for pending jobs.
            try {
                // Log payment details for debugging (ensure these are persisted on the order)
                Log::info('AutoPrint enqueue payment details', [
                    'order_id' => $event->order->id,
                    'cash_received' => $event->order->cash_received ?? null,
                    'cash_change' => $event->order->cash_change ?? null,
                ]);

                \App\Models\PrintJob::create([
                    'order_id' => $event->order->id,
                    'status' => 'pending',
                    'cash_received' => $event->order->cash_received ?? null,
                    'cash_change' => $event->order->cash_change ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create PrintJob: ' . $e->getMessage());
            }

        } else {
            Log::info("❌ AUTO-PRINT DISABLED - Skipping order: " . $event->order->id);
        }
        
        Log::info("✅ AutoPrintInvoice Listener COMPLETED");
    }
}