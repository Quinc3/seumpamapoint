<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $baseQuery = $this->getFilteredQuery();
        $totalOrders = $baseQuery->count();
        
        // Count by status (exclude cancelled dari total revenue)
        $newCount = $baseQuery->clone()->where('status', 'new')->count();
        $processingCount = $baseQuery->clone()->where('status', 'processing')->count();
        $completedCount = $baseQuery->clone()->where('status', 'completed')->count();
        
        // Total Revenue HANYA dari order yang TIDAK cancelled dan paid
        $totalRevenue = $baseQuery->clone()
            ->where('status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI
            ->where('payment_status', 'paid')
            ->sum('total_payment');

        return [
            Stat::make('New Orders', $newCount)
                ->description($this->getStatusDescription($newCount, $totalOrders, 'waiting for process'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->chart($this->generateChartData($newCount)),

            Stat::make('In Progress', $processingCount)
                ->description($this->getStatusDescription($processingCount, $totalOrders, 'being processed'))
                ->descriptionIcon('heroicon-m-cog-6-tooth') 
                ->color('warning')
                ->chart($this->generateChartData($processingCount)),

            Stat::make('Completed', $completedCount)
                ->description($this->getStatusDescription($completedCount, $totalOrders, 'successfully completed'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart($this->generateChartData($completedCount)),

            Stat::make('Total Revenue', 'IDR ' . number_format($totalRevenue, 0, ',', '.'))
                ->description($totalOrders . ' orders • ' . $this->getFilterDescription())
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart($this->generateRevenueChart($totalRevenue)),
        ];
    }

    protected function getFilteredQuery()
    {
        $query = Order::query();
        $filters = request()->query('tableFilters', []);

        if (isset($filters['created_at'])) {
            if (!empty($filters['created_at']['created_from'])) {
                $query->whereDate('created_at', '>=', $filters['created_at']['created_from']);
            }
            if (!empty($filters['created_at']['created_until'])) {
                $query->whereDate('created_at', '<=', $filters['created_at']['created_until']);
            }
        } else {
            $query->whereDate('created_at', today());
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        return $query;
    }

    protected function getFilterDescription(): string
    {
        $filters = request()->query('tableFilters', []);
        
        if (isset($filters['today']) && $filters['today']) return 'Today';
        if (isset($filters['yesterday']) && $filters['yesterday']) return 'Yesterday';
        if (isset($filters['last_7_days']) && $filters['last_7_days']) return 'Last 7 Days';
        
        if (isset($filters['created_at'])) {
            $from = $filters['created_at']['created_from'] ?? null;
            $until = $filters['created_at']['created_until'] ?? null;
            if ($from || $until) return ($from ?: 'Start') . ' - ' . ($until ?: 'Now');
        }
        
        return 'Today';
    }

    protected function getStatusDescription(int $count, int $total, string $statusText): string
    {
        if ($total === 0) return 'No data available';
        
        $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
        return "{$percentage}% • {$count} orders {$statusText}";
    }

    protected function generateChartData(int $count): array
    {
        return [
            max(1, $count - 2),
            max(1, $count - 1),
            $count,
            $count + 1,
            $count + 2,
            $count + 1,
        ];
    }

    protected function generateRevenueChart(float $revenue): array
    {
        $base = $revenue / 100000;
        return [
            $base * 0.6,
            $base * 0.8, 
            $base * 1.2,
            $base * 1.5,
            $base * 1.3,
            $base * 1.1,
        ];
    }

    public static function shouldAutoRefresh(): bool
    {
        return true;
    }

    public static function getAutoRefreshInterval(): ?string
    {
        return '45s';
    }
}