<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\InvoiceSetting;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // DOWNLOAD INVOICE
    public function downloadInvoice(Order $order)
    {
        $order->load('orderDetails.product');
        $settings = InvoiceSetting::getSettings();
        $printerSettings = PrinterSetting::getSettings();

        $printService = new \App\Services\ThermalPrintService();
        $content = $printService->generateSimpleReceipt($order, $settings, $printerSettings);

        // VERSION SUPER SIMPLE
        $html = '<pre style="font-family: Courier New, monospace; font-size: 11px; line-height: 1.1; margin: 0; padding: 10px; width: 70mm; white-space: pre; letter-spacing: 0.3px;">' . htmlspecialchars($content) . '</pre>';

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper([0, 0, 226, 841], 'portrait');

        $pdf->setOption('margin-top', 2);
        $pdf->setOption('margin-bottom', 2);
        $pdf->setOption('margin-left', 2);
        $pdf->setOption('margin-right', 5);

        return $pdf->download("invoice-{$order->id}.pdf");
    }

    // DOWNLOAD SALES REPORT (BULK)
    public function downloadSalesReport(Request $request)
    {
        $orderIds = $request->get('orders', []);
        $orders = Order::with('orderDetails.product')
            ->whereIn('id', $orderIds)
            ->get();

        $pdf = Pdf::loadView('reports.sales-report', compact('orders'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // DOWNLOAD MONTHLY REPORT
    public function downloadMonthlyReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $orders = Order::with('orderDetails.product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total_payment');
        $totalOrders = $orders->count();
        $totalProducts = $orders->sum(function ($order) {
            return $order->orderDetails->sum('qty');
        });

        $pdf = Pdf::loadView('reports.monthly-report', compact(
            'orders',
            'totalSales',
            'totalOrders',
            'totalProducts',
            'month',
            'year'
        ));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("monthly-report-{$year}-{$month}.pdf");
    }

    // DOWNLOAD DAILY REPORT
    public function downloadDailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $orders = Order::with('orderDetails.product')
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $totalSales = $orders->sum('total_payment');
        $totalOrders = $orders->count();
        $totalProducts = $orders->sum(function ($order) {
            return $order->orderDetails->sum('qty');
        });

        $pdf = Pdf::loadView('reports.daily-report', compact(
            'orders',
            'totalSales',
            'totalOrders',
            'totalProducts',
            'date'
        ));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download("daily-report-{$date}.pdf");
    }

    // DOWNLOAD REPORT DENGAN EXCEL SUPPORT
    public function downloadReport(Request $request)
    {
        $reportType = $request->get('report_type');
        $format = $request->get('format', 'pdf');

        $query = Order::with('orderDetails.product');

        // Apply filters based on report type
        switch ($reportType) {
            case 'daily':
                $date = $request->get('date', now()->format('Y-m-d'));
                $query->whereDate('created_at', $date);
                $title = "Daily Report - {$date}";
                break;

            case 'monthly':
                $month = $request->get('month', now()->month);
                $year = $request->get('year', now()->year);
                $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
                $title = "Monthly Report - {$month}/{$year}";
                break;

            case 'custom':
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $title = "Custom Report - {$startDate} to {$endDate}";
                break;

            case 'filtered':
                // Use current table filters
                $title = "Filtered Report";
                break;
        }

        $orders = $query->where('status', 'completed')->get();

        // EXCEL EXPORT
        if ($format === 'excel') {
            return Excel::download(new OrdersExport($orders), Str::slug($title) . '.xlsx');
        }

        // PDF EXPORT
        $totalSales = $orders->sum('total_payment');
        $totalOrders = $orders->count();
        $totalProducts = $orders->sum(function ($order) {
            return $order->orderDetails->sum('qty');
        });

        $pdf = Pdf::loadView('reports.sales-report', compact('orders', 'title', 'totalSales', 'totalOrders', 'totalProducts'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(Str::slug($title) . '.pdf');
    }

    // HELPER METHOD untuk paper size
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