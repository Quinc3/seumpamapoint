<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Facades\Log;

class UsbPrintService
{
    protected string $printerName;

    public function __construct(string $printerName = "Brother HL-T4000DW")
    {
        $this->printerName = $printerName;
    }

    /**
     * Print struk ke printer USB
     */
    public function printReceipt(string $content): array
    {
        try {
            $connector = new WindowsPrintConnector($this->printerName);
            $printer = new Printer($connector);

            $printer->text($content . "\n");
            $printer->cut();
            $printer->close();

            Log::info("🖨️ USB Print success for printer: {$this->printerName}");
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error("❌ USB Print failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Alias supaya bisa pakai ->print() di pemanggilan lama
     */
    public function print(string $content): array
    {
        return $this->printReceipt($content);
    }
}
