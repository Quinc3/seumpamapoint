<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-radius: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #e8f4fd; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="summary">
        <h3>Summary</h3>
        <p>Total Orders: {{ $totalOrders }}</p>
        <p>Total Sales: IDR {{ number_format($totalSales, 0, ',', '.') }}</p>
        <p>Total Products Sold: {{ $totalProducts }}</p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Status</th>
                <th>Payment</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ ucfirst($order->payment_status) }}</td>
                <td class="text-right">IDR {{ number_format($order->total_payment, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Grand Total</td>
                <td class="text-right">IDR {{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>