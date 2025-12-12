<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\InvoiceSetting;
use App\Models\PrinterSetting;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function downloadInvoice($orderId)
    {
        try {
            $order = Order::with(['orderDetails.product'])->findOrFail($orderId);
            $settings = InvoiceSetting::getSettings();
            $printerSettings = PrinterSetting::getSettings();

            $pdf = app('dompdf.wrapper');

            $html = view('invoice', [
                'order' => $order,
                'settings' => $settings,
                'printerSettings' => $printerSettings
            ])->render();

            // Paper size setting tanpa panggil getPaperDimensions()
            $paperSize = $this->getPaperSize($printerSettings->paper_size);

            $pdf->loadHTML($html)
                ->setPaper($paperSize, 'portrait')
                ->setOption('dpi', 72)
                ->setOption('defaultFont', 'DejaVu Sans Mono');

            $filename = "invoice-{$order->id}-" . now()->format('Y-m-d-H-i') . ".pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error("Invoice download error: " . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }

    protected function getPaperSize($paperSize)
    {
        switch ($paperSize) {
            case '58mm':
                return [0, 0, 164.41, 841.89]; // 58mm width in points
            case '80mm':
                return [0, 0, 226.77, 841.89]; // 80mm width in points  
            default:
                return 'a4';
        }
    }
}