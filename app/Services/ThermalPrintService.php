<?php

namespace App\Services;

use App\Models\Order;
use App\Models\InvoiceSetting;
use App\Models\PrinterSetting;
use Illuminate\Support\Facades\Log;

class ThermalPrintService
{
    public function printInvoice(Order $order)
    {
        try {
            $printerSettings = PrinterSetting::getSettings();
            $invoiceSettings = InvoiceSetting::getSettings();

            Log::info("🖨️ === PRINT INVOICE DEBUG START ===");
            Log::info("📦 Order ID: " . $order->id);
            Log::info("💰 Total Payment: " . $order->total_payment);
            Log::info("⚙️ Auto-print setting: " . ($printerSettings->auto_print ? 'ENABLED' : 'DISABLED'));

            // DEBUG: Load relationship
            $order->load(['orderDetails.product']);

            Log::info("🔍 Order Details Count: " . $order->orderDetails->count());

            // DEBUG: Check each item
            foreach ($order->orderDetails as $index => $detail) {
                Log::info("📝 Item {$index}:", [
                    'product_name' => $detail->product->name ?? 'NO NAME',
                    'qty' => $detail->qty,
                    'price' => $detail->price,
                    'subtotal' => $detail->subtotal
                ]);
            }

            if (!$printerSettings->auto_print) {
                Log::info("❌ Auto-print disabled in settings");
                return;
            }

            Log::info("✅ Auto-print enabled, generating receipt...");

            // DEBUG: Check generated content
            $content = $this->generateSimpleReceipt($order, $invoiceSettings, $printerSettings);
            Log::info("📄 GENERATED CONTENT:\n" . $content);

            $this->printDirectToPrinter($order);
            Log::info("🖨️ === PRINT INVOICE DEBUG END ===");

        } catch (\Exception $e) {
            Log::error('❌ Print invoice failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }


    protected function printDirectToPrinter(Order $order)
    {
        try {
            $printerSettings = PrinterSetting::getSettings();
            $invoiceSettings = InvoiceSetting::getSettings();
            $printerName = $printerSettings->printer_name;

            Log::info('🖨️ Printing directly to: ' . ($printerName ?: 'DEFAULT PRINTER'));

            $content = $this->generateSimpleReceipt($order, $invoiceSettings, $printerSettings);

            // Print multiple copies jika diperlukan
            $copies = $printerSettings->copies ?: 1;
            for ($i = 1; $i <= $copies; $i++) {
                Log::info("🖨️ Printing copy {$i} of {$copies}");
                $output = $this->executePowerShellPrint($content, $printerName);
                Log::info("🖨️ Copy {$i} output: " . ($output ?: 'SUCCESS'));

                // Delay kecil antara copies
                if ($i < $copies) {
                    sleep(1);
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('❌ Direct print failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function executePowerShellPrint($content, $printerName = null)
    {
        $tempFile = storage_path('app/temp/print-' . time() . '.txt');
        file_put_contents($tempFile, $content);

        if ($printerName) {
            $command = "powershell -Command \"Get-Content '{$tempFile}' | Out-Printer -Name '{$printerName}'\"";
        } else {
            $command = "powershell -Command \"Get-Content '{$tempFile}' | Out-Printer\"";
        }

        Log::info("💻 Executing PowerShell: {$command}");
        $output = shell_exec($command . " 2>&1");
        unlink($tempFile);

        return $output;
    }

    public function generateSimpleReceipt($order, $invoiceSettings, $printerSettings)
    {
        $width = $printerSettings->getPaperWidth();
        $content = "";

        // HEADER
        $content .= str_pad($invoiceSettings->company_name, $width, " ", STR_PAD_BOTH) . "\n";

        if ($invoiceSettings->company_address) {
            $addressLines = $this->splitText($invoiceSettings->company_address, $width);
            foreach ($addressLines as $line) {
                $content .= str_pad($line, $width, " ", STR_PAD_BOTH) . "\n";
            }
        }

        if ($invoiceSettings->company_phone) {
            $content .= str_pad("Telp: " . $invoiceSettings->company_phone, $width, " ", STR_PAD_BOTH) . "\n";
        }

        $content .= str_repeat("=", $width) . "\n";  // Garis tebal untuk header/footer

        // INVOICE INFO
        $content .= str_pad($invoiceSettings->invoice_title . " #" . $order->id, $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("-", $width) . "\n";  // Garis tipis untuk section

        if ($order->customer_name) {
            $content .= "Customer: " . $order->customer_name . "\n";
        }

        $content .= "Date: " . $order->created_at->format('d/m/Y H:i') . "\n";
        $content .= "Status: " . strtoupper($order->payment_status) . "\n";
        $content .= "Method: " . strtoupper($order->payment_method) . "\n";
        $content .= str_repeat("-", $width) . "\n";  // Garis tipis

        // ITEMS HEADER
        $content .= "ITEM" . str_repeat(" ", $width - 20) . "QTY   SUBTOTAL\n";
        $content .= str_repeat("-", $width) . "\n";  // Garis tipis

        // ITEMS
        foreach ($order->orderDetails as $detail) {
            $productName = $detail->product->name ?? 'Unknown Product';

            // Format: Nama produk di kiri, qty di tengah, subtotal di kanan
            $nameMaxLength = $width - 15;
            $nameLine = substr($productName, 0, $nameMaxLength);
            $qtyText = $detail->qty;
            $subtotalText = "IDR " . number_format($detail->subtotal, 0, ',', '.');

            // Hitung spasi yang dibutuhkan
            $spacesNeeded = $width - strlen($nameLine) - strlen($qtyText) - strlen($subtotalText) - 3;

            $content .= $nameLine;
            $content .= str_repeat(" ", $spacesNeeded);
            $content .= $qtyText . "   " . $subtotalText . "\n\n";
        }

        $content .= str_repeat("-", $width) . "\n";  // Garis tipis

        // TOTALS
        $subtotalStr = "IDR " . number_format($order->total_price, 0, ',', '.');
        $totalStr = "IDR " . number_format($order->total_payment, 0, ',', '.');

        $content .= "Subtotal:" . str_repeat(" ", $width - strlen("Subtotal:") - strlen($subtotalStr)) . $subtotalStr . "\n";

        if ($order->discount > 0) {
            $discountStr = "IDR -" . number_format($order->discount_amount, 0, ',', '.');
            $content .= "Discount:" . str_repeat(" ", $width - strlen("Discount:") - strlen($discountStr)) . $discountStr . "\n";
        }

        $content .= str_repeat("-", $width) . "\n";  // Garis tipis
        $content .= "TOTAL:" . str_repeat(" ", $width - strlen("TOTAL:") - strlen($totalStr)) . $totalStr . "\n";

        // CASH DETAILS
        if ($order->payment_method === 'cash' && $order->cash_received > 0) {
            $content .= str_repeat("-", $width) . "\n";  // Garis tipis

            $cashReceivedStr = "IDR " . number_format($order->cash_received, 0, ',', '.');
            $cashChangeStr = "IDR " . number_format($order->cash_change ?? 0, 0, ',', '.');

            $content .= "Cash Received:" . str_repeat(" ", $width - strlen("Cash Received:") - strlen($cashReceivedStr)) . $cashReceivedStr . "\n";
            $content .= "Change:" . str_repeat(" ", $width - strlen("Change:") - strlen($cashChangeStr)) . $cashChangeStr . "\n";
        }

        // FOOTER
        $content .= str_repeat("=", $width) . "\n";  // Garis tebal untuk footer

        $footerText = $invoiceSettings->footer_text ?: 'Thank you for your order!';
        $content .= str_pad($footerText, $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("Have a nice day!", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("Printed: " . now()->format('d/m/Y H:i'), $width, " ", STR_PAD_BOTH) . "\n";

        return $content;
    }

    /**
     * TEST PRINT - Fitur test print terpisah
     */
    public function testPrint($testType = 'simple')
    {
        try {
            $printerSettings = PrinterSetting::getSettings();
            $invoiceSettings = InvoiceSetting::getSettings();
            $printerName = $printerSettings->printer_name;

            Log::info("=== TEST PRINT ({$testType}) START ===");

            $content = "";

            switch ($testType) {
                case 'full':
                    $content = $this->generateFullTest($invoiceSettings, $printerSettings);
                    break;
                case 'alignment':
                    $content = $this->generateAlignmentTest($printerSettings);
                    break;
                case 'thermal':
                    $content = $this->generateThermalTest($printerSettings);
                    break;
                default:
                    $content = $this->generateSimpleTest($invoiceSettings, $printerSettings);
                    break;
            }

            $output = $this->executePowerShellPrint($content, $printerName);

            Log::info("Test print output: " . ($output ?: 'SUCCESS'));
            Log::info("=== TEST PRINT ({$testType}) END ===");

            return [
                'success' => true,
                'message' => "Test print ({$testType}) completed",
                'printer' => $printerName,
                'paper_size' => $printerSettings->paper_size,
                'test_type' => $testType,
                'output' => $output ?: 'No output'
            ];

        } catch (\Exception $e) {
            Log::error('Test print failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Various test patterns
     */
    protected function generateSimpleTest($invoiceSettings, $printerSettings)
    {
        $width = $printerSettings->getPaperWidth();
        $content = "";

        $content .= str_repeat("=", $width) . "\n";
        $content .= str_pad("TEST PRINT", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("=", $width) . "\n";
        $content .= "Company: " . $invoiceSettings->company_name . "\n";
        $content .= "Printer: " . ($printerSettings->printer_name ?: 'DEFAULT') . "\n";
        $content .= "Paper  : " . $printerSettings->paper_size . "\n";
        $content .= "Date   : " . date('d/m/Y H:i:s') . "\n";
        $content .= "Status : TEST SUCCESS\n";
        $content .= str_repeat("-", $width) . "\n";
        $content .= str_pad("✓ Print System Working", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("✓ Thermal Printer OK", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("=", $width) . "\n";
        $content .= "\n\n";

        return $content;
    }

    protected function generateFullTest($invoiceSettings, $printerSettings)
    {
        $width = $printerSettings->getPaperWidth();
        $content = "";

        $content .= str_repeat("=", $width) . "\n";
        $content .= str_pad("FULL TEST PRINT", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("=", $width) . "\n";

        // Test items
        $content .= "ITEM" . str_repeat(" ", $width - 15) . "QTY   HARGA\n";
        $content .= str_repeat("-", $width) . "\n";
        $content .= "Test Product 1" . str_repeat(" ", $width - 29) . "  1    10.000\n";
        $content .= "Test Product 2 Long Name" . str_repeat(" ", $width - 40) . "  2    25.000\n";
        $content .= "Test Item 3" . str_repeat(" ", $width - 26) . "  1     5.000\n";
        $content .= str_repeat("-", $width) . "\n";
        $content .= "SUBTOTAL : 40.000\n";
        $content .= "DISCOUNT : -5.000\n";
        $content .= str_repeat("-", $width) . "\n";
        $content .= "TOTAL    : 35.000\n";
        $content .= str_repeat("=", $width) . "\n";

        $content .= "Printer: " . $printerSettings->printer_name . "\n";
        $content .= "Paper  : " . $printerSettings->paper_size . "\n";
        $content .= "Connection: " . $printerSettings->printer_connection . "\n";
        $content .= "Copies : " . $printerSettings->copies . "\n";
        $content .= str_repeat("-", $width) . "\n";
        $content .= str_pad("✓ FULL TEST COMPLETED", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= "\n\n";

        return $content;
    }

    protected function generateAlignmentTest($printerSettings)
    {
        $width = $printerSettings->getPaperWidth();
        $content = "";

        $content .= str_pad("ALIGNMENT TEST", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("=", $width) . "\n";
        $content .= "Left" . str_repeat(" ", $width - 8) . "Right\n";
        $content .= str_repeat("-", $width) . "\n";

        for ($i = 1; $i <= 5; $i++) {
            $left = "Item " . $i;
            $right = number_format($i * 1000, 0, ',', '.');
            $content .= $left . str_repeat(" ", $width - strlen($left) - strlen($right)) . $right . "\n";
        }

        $content .= str_repeat("=", $width) . "\n";
        $content .= str_pad("CENTER TEXT", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("ALIGNMENT OK", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= "\n\n";

        return $content;
    }

    protected function generateThermalTest($printerSettings)
    {
        $width = $printerSettings->getPaperWidth();
        $content = "";

        $content .= str_pad("THERMAL PRINTER TEST", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_repeat("=", $width) . "\n";
        $content .= "Paper Feed Test:\n";

        for ($i = 1; $i <= 3; $i++) {
            $content .= "Line " . $i . " - " . str_repeat(".", $width - 10) . "\n";
        }

        $content .= str_repeat("-", $width) . "\n";
        $content .= "Cut Position Test:\n";
        $content .= "Text should be above cut line\n";
        $content .= str_repeat(".", $width) . "\n";
        $content .= str_repeat(".", $width) . "\n";
        $content .= str_repeat(".", $width) . "\n";
        $content .= "↓↓↓ CUT HERE ↓↓↓\n";
        $content .= "\n\n\n";

        return $content;
    }

    /**
     * Helper untuk split text
     */
    protected function splitText($text, $maxLength)
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxLength) {
                $currentLine .= ($currentLine === '' ? '' : ' ') . $word;
            } else {
                $lines[] = $currentLine;
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    /**
     * List available printers
     */
    public function getAvailablePrinters()
    {
        try {
            $command = "powershell -Command \"Get-Printer | Format-Table Name, DriverName, PortName, PrinterStatus -AutoSize\"";
            $output = shell_exec($command . " 2>&1");

            return [
                'success' => true,
                'printers' => $output ?: 'No printers found'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}