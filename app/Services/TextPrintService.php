<?php

namespace App\Services;

use App\Models\Order;

class TextPrintService
{
    /**
     * Generate text struk (ESC/POS compatible)
     * RETURN STRING — BUKAN PRINT
     */
    public function generateInvoiceText(Order $order): string
    {
        $settings = \App\Models\InvoiceSetting::getSettings();

        $content  = "";
        $content .= str_pad($settings->company_name, 32, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("INVOICE #" . $order->id, 32, " ", STR_PAD_BOTH) . "\n";
        $content .= "--------------------------------\n";

        foreach ($order->orderDetails as $detail) {
            $name = substr($detail->product->name, 0, 20);
            $content .= str_pad($name, 20);
            $content .= "x{$detail->qty} ";
            $content .= number_format($detail->subtotal, 0, ',', '.') . "\n";
        }

        $content .= "--------------------------------\n";
        $content .= "TOTAL : " . number_format($order->total_payment, 0, ',', '.') . "\n";
        $content .= "STATUS: " . strtoupper($order->payment_status) . "\n\n";
        $content .= "Terima kasih\n";
        $content .= "\x1B\x69"; // Cut paper

        return $content;
    }
}
