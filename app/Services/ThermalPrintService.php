<?php

namespace App\Services;

use App\Models\Order;
use App\Models\InvoiceSetting;
use App\Models\PrinterSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\UsbPrintService;

class ThermalPrintService
{
    /**
     * ENTRY POINT
     * Print invoice. Jika printer USB, langsung kirim ke printer. 
     * Jika shared hosting / lainnya, simpan ke storage saja.
     */
    public function printInvoice(Order $order): array
    {
        $invoiceSettings = InvoiceSetting::getSettings();
        $printerSettings = PrinterSetting::getSettings();

        $order->load(['items.product']);

        return [
            'success' => true,
            'mode' => 'browser',
            'content' => $this->generateSimpleReceipt($order, $invoiceSettings, $printerSettings),
        ];
    }


    /**
     * Simpan struk ke storage (TXT)
     */
    protected function storeReceipt(int $orderId, string $content): string
    {
        $filename = 'receipts/receipt-' . $orderId . '.txt';
        Storage::disk('local')->put($filename, $content);
        return storage_path('app/' . $filename);
    }

    /**
     * Generate struk text
     */
    public function generateSimpleReceipt($order, $invoiceSettings, $printerSettings): string
    {
        $width = $printerSettings->getPaperWidth();
        $content = '';

        // Header
        $content .= str_pad($invoiceSettings->company_name, $width, ' ', STR_PAD_BOTH) . "\n";
        $content .= str_repeat('=', $width) . "\n";
        $content .= "Invoice #" . $order->id . "\n";
        $content .= "Date   : " . $order->created_at->format('d/m/Y H:i') . "\n";

        // Nama kasir
        $cashier = $order->user->name ?? 'Unknown';
        $content .= "Cashier: " . $cashier . "\n";

        // Nama customer jika ada
        if (!empty($order->customer_name)) {
            $content .= "Customer: " . $order->customer_name . "\n";
        }

        $content .= "Status : " . strtoupper($order->payment_status) . "\n";
        $content .= str_repeat('-', $width) . "\n";

        // Items
        $order->loadMissing('items.product'); // pastikan relasi di-load
        foreach ($order->items as $detail) {
            $name = $detail->product->name ?? 'Unknown';
            $qty = $detail->qty;
            $subtotal = number_format($detail->subtotal, 0, ',', '.');

            $content .= "{$name} x{$qty}\n";
            $content .= str_pad("IDR {$subtotal}", $width, ' ', STR_PAD_LEFT) . "\n";
        }

        $content .= str_repeat('-', $width) . "\n";

        // Total
        $content .= str_pad(
            'TOTAL: IDR ' . number_format($order->total_payment, 0, ',', '.'),
            $width,
            ' ',
            STR_PAD_LEFT
        ) . "\n";

        $content .= str_repeat('=', $width) . "\n";
        // Payment details (received and change) if available
        if (! is_null($order->cash_received) && $order->cash_received !== '') {
            $received = 'IDR ' . number_format($order->cash_received, 0, ',', '.');
            $content .= str_pad('Cash Received: ' . $received, $width, ' ', STR_PAD_LEFT) . "\n";
        }

        if (! is_null($order->cash_change) && $order->cash_change !== '') {
            $change = 'IDR ' . number_format($order->cash_change, 0, ',', '.');
            $content .= str_pad('Change:        ' . $change, $width, ' ', STR_PAD_LEFT) . "\n";
        }

        $content .= str_repeat('=', $width) . "\n";
        $content .= str_pad(
            $invoiceSettings->footer_text ?? 'Thank you',
            $width,
            ' ',
            STR_PAD_BOTH
        ) . "\n";

        return $content;
    }


    /**
     * Test print (shared hosting safe)
     */
    public function testPrint(): array
    {
        return [
            'success' => true,
            'mode' => 'shared_hosting',
            'message' => 'Printer test disabled on shared hosting'
        ];
    }

    /**
     * Printer list tidak tersedia
     */
    public function getAvailablePrinters(): array
    {
        return [
            'success' => false,
            'message' => 'Printer access not available on shared hosting'
        ];
    }
}
