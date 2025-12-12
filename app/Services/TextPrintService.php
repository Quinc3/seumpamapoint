<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class TextPrintService
{
    public function printTextInvoice(Order $order)
    {
        try {
            $settings = \App\Models\InvoiceSetting::getSettings();
            
            // Buat text content untuk thermal printer
            $content = $this->generateTextContent($order, $settings);
            
            // Simpan ke file text
            $textPath = storage_path('app/temp/invoice-' . $order->id . '.txt');
            file_put_contents($textPath, $content);
            
            // Print text file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "print /D:\"Microsoft Print to PDF\" \"$textPath\" 2>&1";
            } else {
                $command = "lp \"$textPath\" 2>&1";
            }
            
            $output = shell_exec($command);
            Log::info("Text print output: {$output}");
            
            // Cleanup
            sleep(1);
            if (file_exists($textPath)) {
                unlink($textPath);
            }
            
        } catch (\Exception $e) {
            Log::error('Text print failed: ' . $e->getMessage());
        }
    }
    
    protected function generateTextContent($order, $settings)
    {
        $content = "";
        $content .= str_pad($settings->company_name, 32, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad($settings->invoice_title . " #" . $order->id, 32, " ", STR_PAD_BOTH) . "\n";
        $content .= "--------------------------------\n";
        
        foreach ($order->orderDetails as $detail) {
            $name = substr($detail->product->name, 0, 20);
            $content .= $name . str_repeat(" ", 20 - strlen($name));
            $content .= "x" . $detail->qty . " ";
            $content .= number_format($detail->subtotal, 0, ',', '.') . "\n";
        }
        
        $content .= "--------------------------------\n";
        $content .= "TOTAL: " . number_format($order->total_payment, 0, ',', '.') . "\n";
        $content .= "Status: " . strtoupper($order->payment_status) . "\n";
        $content .= "Thank you!\n";
        $content .= "\x1B\x69"; // Cut command untuk thermal printer
        
        return $content;
    }
}