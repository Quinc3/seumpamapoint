<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Panel;

class CustomLogin extends BaseLogin
{
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();
        
        // Tambahkan CSS melalui data
        $data['styles'] = array_merge($data['styles'] ?? [], [
            $this->getBackgroundStyles()
        ]);
        
        return $data;
    }
    
    protected function getBackgroundStyles(): string
    {

        $bgImage = asset('images/login-background.jpg');
        
        return <<<CSS
        <style>
            /* Background untuk halaman login */
            body.filament-login-page {
                background: 
                    linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)),
                    url('{$bgImage}') !important;
                background-size: cover !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                min-height: 100vh !important;
            }
            
            /* Styling untuk card login */
            .fi-simple-page .fi-simple-main {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px) !important;
                border-radius: 16px !important;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }
        </style>
        CSS;
    }
}