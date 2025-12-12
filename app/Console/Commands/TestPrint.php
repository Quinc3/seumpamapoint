<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\ThermalPrintService;
use Barryvdh\DomPDF\Facade\Pdf; // ← TAMBAH INI
use App\Models\InvoiceSetting; // ← TAMBAH INI

class TestPrint extends Command
{
    protected $signature = 'test:print {order_id}';
    protected $description = 'Test print functionality';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::with('orderDetails.product')->find($orderId);
        
        if (!$order) {
            $this->error('Order not found');
            return;
        }

        $this->info('=== TEST PRINT DEBUG ===');
        $this->info('Order: ' . $order->id);
        $this->info('Total: ' . $order->total_payment);
        $this->info('Payment Status: ' . $order->payment_status);
        
        // Cek settings
        $settings = InvoiceSetting::getSettings();
        $this->info('Auto Print: ' . ($settings->auto_print ? 'YES' : 'NO'));
        $this->info('Printer: ' . ($settings->printer_name ?: 'DEFAULT'));
        $this->info('Connection: ' . $settings->printer_connection);
        
        // Test PDF generation
        $this->info('Generating PDF...');
        try {
            $pdf = Pdf::loadView('reports.invoice', compact('order', 'settings'));
            $pdfPath = storage_path('app/test-debug-' . $order->id . '.pdf');
            $pdf->save($pdfPath);
            $this->info('✅ PDF saved: ' . $pdfPath);
            $this->info('✅ PDF size: ' . filesize($pdfPath) . ' bytes');
        } catch (\Exception $e) {
            $this->error('❌ PDF generation failed: ' . $e->getMessage());
            return;
        }
        
        // Test shell command execution
        $this->info('Testing shell command execution...');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $testCommand = 'echo "Hello Printer Test"';
        } else {
            $testCommand = 'echo "Hello Printer Test"';
        }
        
        $output = shell_exec($testCommand);
        $this->info('Shell test output: ' . ($output ? trim($output) : 'NO OUTPUT'));
        
        // Test actual printing
        $this->info('Testing actual print...');
        $printService = new ThermalPrintService();
        $printService->printInvoice($order);
        
        $this->info('✅ Print command executed');
        $this->info('📋 Check storage/logs/laravel.log for detailed print logs');
        $this->info('📄 Test PDF: ' . $pdfPath);
    }
}