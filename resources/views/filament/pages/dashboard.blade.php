<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Account Widget - Full Width -->
        <div class="w-full">
            @livewire(\Filament\Widgets\AccountWidget::class)
        </div>

        <!-- 2 Columns Layout - FLEXBOX -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Stats Overview (KIRI) -->
            <div class="flex-1">
                @livewire(\App\Filament\Widgets\StatsOverviewWidget::class)
            </div>

            <!-- Top Selling Products (KANAN) -->
            <div class="flex-1">
                @livewire(\App\Filament\Widgets\TopSellingProductsWidget::class)
            </div>
        </div>
    </div>
</x-filament-panels::page>