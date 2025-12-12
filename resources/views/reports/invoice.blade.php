<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        /* RESET & BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Monaco, monospace;
        }

        body {
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            max-width: 72mm;
            margin: 0 auto;
            padding: 5px;
        }

        /* HEADER STYLES */
        .receipt-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #000;
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 10px;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .invoice-title {
            font-weight: bold;
            font-size: 13px;
            margin: 4px 0;
            text-transform: uppercase;
        }

        /* INFO ROWS */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .status-paid {
            color: #2e7d32;
            font-weight: bold;
        }

        .status-pending {
            color: #f57c00;
            font-weight: bold;
        }

        /* DIVIDER */
        .divider {
            border-bottom: 1px dashed #000;
            margin: 6px 0;
        }

        /* TABLE STYLES */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .items-table th {
            text-align: left;
            padding: 3px 2px;
            border-bottom: 1px dotted #000;
            font-weight: bold;
        }

        .items-table td {
            padding: 3px 2px;
            border-bottom: 1px dotted #eee;
        }

        .text-right {
            text-align: right;
        }

        /* TOTALS SECTION */
        .totals-section {
            margin: 8px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-weight: bold;
        }

        .discount-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            color: #d32f2f;
        }

        /* PAYMENT SUMMARY */
        .payment-summary {
            background: #f5f5f5;
            padding: 6px;
            margin: 8px 0;
            border-radius: 3px;
        }

        .summary-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* FOOTER */
        .receipt-footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px dashed #000;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .generated-info {
            font-size: 9px;
            color: #666;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .payment-summary {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @php
        $settings = \App\Models\InvoiceSetting::getSettings();
        $printerSettings = \App\Models\PrinterSetting::getSettings();

        // Hitung change/kembalian jika cash payment
        $change = 0;
        $cashReceived = $order->cash_received ?? $order->total_payment;
        if ($order->payment_method === 'cash' && $order->payment_status === 'paid') {
            $change = max(0, $cashReceived - $order->total_payment);
        }
    @endphp

    <!-- HEADER -->
    <div class="receipt-header">
        <div class="company-name">{{ $settings->company_name ?? 'SEUMPAMA BUNGA' }}</div>
        <div class="company-address">
            {{ $settings->company_address ?? 'Malangnengah Blok No.12A, Kadu Agung, Tigaraksa, Tangerang' }}
        </div>
        <div class="invoice-title">{{ $settings->invoice_title ?? 'INVOICE' }} #{{ $order->id }}</div>
        <div class="receipt-info">
            <div class="info-row">
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                <span class="status-{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- ORDER ITEMS -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td class="text-right">{{ $detail->qty }}</td>
                    <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- TOTALS -->
    <div class="totals-section">
        <div class="total-row">
            <span>SUBTOTAL</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>

        @if($order->discount > 0)
            <div class="discount-row">
                <span>DISCOUNT ({{ $order->discount }}%)</span>
                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="divider"></div>

        <div class="total-row" style="font-size: 13px;">
            <span>TOTAL</span>
            <span>Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
        </div>

        <!-- CASH & CHANGE SECTION -->
        @if($order->payment_method === 'cash' && $order->payment_status === 'paid')
            <div class="divider"></div>
            <div class="total-row">
                <span>CASH</span>
                <span>Rp {{ number_format($cashReceived, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="color: #2e7d32;">
                <span>CHANGE</span>
                <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <!-- PAYMENT SUMMARY -->
    <div class="payment-summary">
        <div class="summary-title">Payment Summary</div>
        <div class="info-row">
            <span>Method:</span>
            <span>{{ strtoupper($order->payment_method) }}</span>
        </div>
        <div class="info-row">
            <span>Status:</span>
            <span class="status-{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span>
        </div>

        @if($order->payment_method === 'cash' && $order->payment_status === 'paid')
            <div class="info-row">
                <span>Cash Received:</span>
                <span>Rp {{ number_format($cashReceived, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span>Change:</span>
                <span style="color: #2e7d32; font-weight: bold;">Rp {{ number_format($change, 0, ',', '.') }}</span>
            </div>
        @endif

        @if($order->payment_status === 'paid')
            <div class="info-row">
                <span>Paid at:</span>
                <span>{{ $order->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="receipt-footer">
        <div class="thank-you">{{ $settings->footer_text ?? 'Thank you for your order!' }}</div>
        <div class="generated-info">Generated: {{ now()->format('d/m/Y H:i') }}</div>

        @if($settings->terms_conditions ?? false)
            <div class="divider"></div>
            <div style="font-size: 8px; margin-top: 4px; line-height: 1.2;">
                {{ $settings->terms_conditions }}
            </div>
        @endif
    </div>

    <!-- CUT MARK FOR THERMAL PRINTER -->
    <div style="text-align: center; margin-top: 10px; font-size: 9px; color: #999;">
        ••••••••••••••••••••••••••••••••
    </div>

    <script>
        // Auto print jika diperlukan
        @if($printerSettings->auto_print ?? false)
            window.onload = function () {
                setTimeout(function () {
                    window.print();
                }, 500);
            };
        @endif
    </script>
</body>
</html>