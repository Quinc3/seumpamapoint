<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        
        return "Cache cleared successfully!";
    }

    public function systemInfo()
    {
        return response()->json([
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'timezone' => config('app.timezone'),
            'environment' => app()->environment(),
        ]);
    }
}