<?php
// app/Filament/Pages/PointOfSale.php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.point-of-sale';
    
    // HIDDEN FROM NAVBAR
    protected static bool $shouldRegisterNavigation = false;
    
    // Optional: remove group and sort
    // protected static ?string $navigationGroup = null;
    // protected static ?int $navigationSort = null;

    public static function getNavigationLabel(): string
    {
        return 'POS System';
    }

    public function getTitle(): string
    {
        return 'Point of Sale';
    }
}