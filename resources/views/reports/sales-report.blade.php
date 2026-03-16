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
        <p>Total Orders: {{ $totalOrders ?? ($orders->count() ?? 0) }}</p>
        <p>Total Sales: IDR {{ number_format($totalSales ?? $orders->sum('total_payment'), 0, ',', '.') }}</p>
        <p>Total Products Sold: {{ $totalProducts ?? $orders->sum(fn($o) => $o->items->sum('qty')) }}</p>

        @if(isset($reportCategoryId) && $reportCategoryId)
            <p>Filtered Category ID: {{ $reportCategoryId }}</p>
        @endif

        @if(isset($reportProductId) && $reportProductId)
            <p>Filtered Product ID: {{ $reportProductId }}</p>
        @endif
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
            <tr>
                <td colspan="5" style="padding:0;">
                    <table class="table" style="margin:0;">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Unknown' }}</td>
                                    <td>{{ $item->product->category->name ?? 'Unknown' }}</td>
                                    <td class="text-right">{{ $item->qty }}</td>
                                    <td class="text-right">IDR {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-right">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
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

    @if(!empty($categoryTotals))
        <h3>Category Breakdown</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryTotals as $cat)
                    <tr>
                        <td>{{ $cat['name'] }}</td>
                        <td class="text-right">{{ $cat['qty'] }}</td>
                        <td class="text-right">IDR {{ number_format($cat['sales'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!empty($productTotals))
        <h3>Product Breakdown (Top Selling)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Sales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productTotals as $p)
                    <tr>
                        <td>{{ $p['name'] }}</td>
                        <td class="text-right">{{ $p['qty'] }}</td>
                        <td class="text-right">IDR {{ number_format($p['sales'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>