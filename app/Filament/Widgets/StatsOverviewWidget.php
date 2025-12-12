<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Today's Sales (hanya yang paid dan TIDAK cancelled)
        $todaySales = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI
            ->sum('total_payment') ?? 0;

        // Yesterday's Sales (hanya yang paid dan TIDAK cancelled)
        $yesterdaySales = Order::whereDate('created_at', $yesterday)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI
            ->sum('total_payment') ?? 0;

        // Handle sales change calculation
        $salesChange = 0;
        $salesDescription = 'No data yesterday';
        $salesIcon = 'heroicon-m-minus';
        $salesColor = 'gray';

        if ($yesterdaySales > 0) {
            $salesChange = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
            $salesDescription = ($salesChange >= 0 ? '↑' : '↓') . number_format(abs($salesChange), 1) . '% vs yesterday';
            $salesIcon = $salesChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
            $salesColor = $salesChange >= 0 ? 'success' : 'danger';
        } elseif ($todaySales > 0) {
            $salesDescription = 'First sales today! ';
            $salesIcon = 'heroicon-m-arrow-trending-up';
            $salesColor = 'success';
        }

        // Today's Orders (hanya yang TIDAK cancelled)
        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI
            ->count();
            
        $yesterdayOrders = Order::whereDate('created_at', $yesterday)
            ->where('status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI
            ->count();

        // Handle orders change calculation
        $ordersChange = 0;
        $ordersDescription = 'No orders yesterday';
        $ordersIcon = 'heroicon-m-minus';
        $ordersColor = 'gray';

        if ($yesterdayOrders > 0) {
            $ordersChange = (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100;
            $ordersDescription = ($ordersChange >= 0 ? '↑' : '↓') . number_format(abs($ordersChange), 1) . '% vs yesterday';
            $ordersIcon = $ordersChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
            $ordersColor = $ordersChange >= 0 ? 'success' : 'danger';
        } elseif ($todayOrders > 0) {
            $ordersDescription = 'First orders today! ';
            $ordersIcon = 'heroicon-m-arrow-trending-up';
            $ordersColor = 'success';
        }

        $todayProfit = $todaySales * 0.3;

        // Products Sold Today (hanya dari order yang TIDAK cancelled)
        $productsSoldToday = OrderDetail::whereHas('order', function ($query) use ($today) {
            $query->whereDate('created_at', $today)
                  ->where('status', '!=', 'cancelled'); // ⬅️ TAMBAHKAN INI
        })->sum('qty') ?? 0;

        return [
            Stat::make('Today Sales', 'IDR ' . number_format($todaySales, 0, ',', '.'))
                ->description($salesDescription)
                ->descriptionIcon($salesIcon)
                ->color($salesColor),

            Stat::make("Today's Orders", $todayOrders)
                ->description($ordersDescription)
                ->descriptionIcon($ordersIcon)
                ->color($ordersColor),

            Stat::make('Estimated Profit', 'IDR ' . number_format($todayProfit, 0, ',', '.'))
                ->description('30% profit margin from sales')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Products Sold', $productsSoldToday)
                ->description('Total items sold today ' . ($productsSoldToday > 0 ? '' : ''))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
        ];
    }
}