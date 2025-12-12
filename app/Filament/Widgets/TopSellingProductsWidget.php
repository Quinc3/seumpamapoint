<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\OrderDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;

class TopSellingProductsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 1;

    public array $data = [];

    public function mount(): void
    {
        $this->data = [
            'period' => 'today',
            'custom_date' => now()->format('Y-m-d'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Filter Period')
                ->schema([
                    Select::make('period')
                        ->options([
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'this_week' => 'This Week',
                            'this_month' => 'This Month',
                            'last_month' => 'Last Month',
                            'custom' => 'Custom Date',
                        ])
                        ->reactive()
                        ->afterStateUpdated(fn($state) => $this->updateTable()),

                    DatePicker::make('custom_date')
                        ->visible(fn($get) => $get('period') === 'custom')
                        ->reactive()
                        ->afterStateUpdated(fn() => $this->updateTable()),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $period = $this->data['period'] ?? 'today';
                $customDate = $this->data['custom_date'] ?? now()->format('Y-m-d');

                return Product::query()
                    ->select([
                        'products.id',
                        'products.name',
                        'products.price',
                        'products.stock',
                        DB::raw('COALESCE(SUM(order_details.qty), 0) as total_sold'),
                        DB::raw('COALESCE(SUM(order_details.subtotal), 0) as total_revenue')
                    ])
                    ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
                    ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
                    ->where('orders.status', '!=', 'cancelled') // ⬅️ TAMBAHKAN INI - exclude cancelled orders
                    ->when($period === 'today', function (Builder $query) {
                        $query->whereDate('orders.created_at', today());
                    })
                    ->when($period === 'yesterday', function (Builder $query) {
                        $query->whereDate('orders.created_at', today()->subDay());
                    })
                    ->when($period === 'this_week', function (Builder $query) {
                        $query->whereBetween('orders.created_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek()
                        ]);
                    })
                    ->when($period === 'this_month', function (Builder $query) {
                        $query->whereMonth('orders.created_at', now()->month)
                            ->whereYear('orders.created_at', now()->year);
                    })
                    ->when($period === 'last_month', function (Builder $query) {
                        $query->whereMonth('orders.created_at', now()->subMonth()->month)
                            ->whereYear('orders.created_at', now()->subMonth()->year);
                    })
                    ->when($period === 'custom', function (Builder $query) use ($customDate) {
                        $query->whereDate('orders.created_at', $customDate);
                    })
                    ->groupBy('products.id', 'products.name', 'products.price', 'products.stock')
                    ->orderBy('total_sold', 'desc')
                    ->limit(8);
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn($record) => $record->name),

                TextColumn::make('total_sold')
                    ->label('Sold')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0))
                    ->color(fn($state) => $state > 0 ? 'success' : 'gray'),

                TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->color('primary'),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
            ])
            ->heading(function () {
                $period = $this->data['period'] ?? 'today';
                $periodLabels = [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'this_week' => 'This Week',
                    'this_month' => 'This Month',
                    'last_month' => 'Last Month',
                    'custom' => 'Custom Date',
                ];

                return 'Top Selling Products - ' . ($periodLabels[$period] ?? 'Today');
            })
            ->description(function () {
                $period = $this->data['period'] ?? 'today';
                $customDate = $this->data['custom_date'] ?? null;

                if ($period === 'custom' && $customDate) {
                    return 'Data for ' . \Carbon\Carbon::parse($customDate)->format('M d, Y');
                }

                return 'Most popular items by quantity sold (excludes cancelled orders)'; // ⬅️ UPDATE DESCRIPTION
            });
    }

    public function updateTable(): void
    {
        $this->dispatch('refreshTable');
    }
}