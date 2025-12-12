<?php
// app/Filament/Pages/PointOfSale.php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.point-of-sale';
    
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'POS System';
    }

    public function getTitle(): string
    {
        return 'Point of Sale';
    }
}