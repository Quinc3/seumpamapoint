<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Events\OrderPaid;
use App\Services\ThermalPrintService;
use App\Models\PrinterSetting;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DebugController extends Controller
{
    // Test receipt content (lihat format)
    public function testReceipt($orderId)
    {
        $order = Order::with(['orderDetails.product'])->find($orderId);

        if (!$order) {
            return "Order tidak ditemukan";
        }

        $printService = new ThermalPrintService();
        $printerSettings = PrinterSetting::getSettings();
        $invoiceSettings = InvoiceSetting::getSettings();

        $content = $printService->generateSimpleReceipt($order, $invoiceSettings, $printerSettings);

        return response($content)->header('Content-Type', 'text/plain');
    }

    // Test print manual
    public function testPrint($orderId)
    {
        $order = Order::with(['orderDetails.product'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        \Log::info("🧪 MANUAL PRINT TEST - Order: " . $order->id);

        $printService = new ThermalPrintService();
        $printService->printInvoice($order);

        return "Manual print test completed untuk order " . $orderId . " - Cek logs!";
    }

    // Test event trigger
    public function testEvent($orderId)
    {
        $order = Order::with(['orderDetails.product'])->find($orderId);

        if (!$order) {
            return "Order not found";
        }

        \Log::info("🧪 TEST EVENT TRIGGER - Order: " . $order->id);

        // Trigger event manually
        event(new OrderPaid($order));

        return "Event triggered for order " . $orderId . " - Check logs!";
    }

    // Debug order events (test model events)
    public function debugOrderEvents($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return "Order not found";
        }

        \Log::info("🧪 DEBUG ORDER EVENTS - Testing order: " . $order->id);
        \Log::info("Current status: " . $order->payment_status);

        // Test 1: Update status (should trigger model event)
        $order->payment_status = 'paid';
        $order->save();

        \Log::info("✅ Status updated to paid - check if auto-print triggered");

        return response()->json([
            'message' => 'Order status updated to paid',
            'order_id' => $order->id,
            'new_status' => $order->payment_status
        ]);
    }

    // List semua order (untuk cari ID)
    public function listOrders()
    {
        $orders = Order::with(['orderDetails'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'payment_status', 'total_payment', 'created_at']);

        return response()->json([
            'total_orders' => $orders->count(),
            'orders' => $orders
        ]);
    }

    // Simple printer test
    public function printerTest()
    {
        $printService = new ThermalPrintService();
        $result = $printService->testPrint('simple');

        return response()->json($result);
    }

    // Buat role (sementara)
    public function makeRoles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kasir']);
        return 'Roles admin & kasir berhasil dibuat';
    }
}