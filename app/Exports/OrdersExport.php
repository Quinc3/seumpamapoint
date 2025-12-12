<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Status', 
            'Payment Status',
            'Payment Method',
            'Total Amount',
            'Discount %',
            'Final Amount',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->created_at->format('d/m/Y H:i'),
            ucfirst($order->status),
            ucfirst($order->payment_status),
            ucfirst($order->payment_method),
            $order->total_price,
            $order->discount . '%',
            $order->total_payment,
        ];
    }
}