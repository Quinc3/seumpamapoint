<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'New' => Tab::make()->query(fn($query) => $query->where('status', 'new')),
            'Processing' => Tab::make()->query(fn($query) => $query->where('status', 'processing')),
            'Cancelled' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
            'Compeleted' => Tab::make()->query(fn($query) => $query->where('status', 'completed')),
        ];
    }

}
