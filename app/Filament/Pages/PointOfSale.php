<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.point-of-sale';
    
    // TAMBAHKAN INI untuk sembunyikan dari navbar
    protected static bool $shouldRegisterNavigation = false;
    
    // Opsional: hapus navigation group dan sort
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