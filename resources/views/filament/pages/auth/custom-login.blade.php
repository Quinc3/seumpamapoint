<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ __('filament-panels::pages/auth/login.title') }} - {{ config('app.name') }}</title>
    
    {{-- Filament Styles --}}
    @filamentStyles
    
    <style>
        :root {
            --primary-50: 239 246 255;
            --primary-100: 219 234 254;
            --primary-500: 59 130 246;
            --primary-600: 37 99 235;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 1rem;
        }
        
        .login-background {
            position: fixed;
            inset: 0;
            z-index: 0;
        }
        
        .login-background-image {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .login-background-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }
        
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 28rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            height: 4rem;
            margin: 0 auto 1rem;
            display: block;
        }
        
        .login-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.5rem;
        }
        
        .login-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        .login-form {
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #4b5563;
        }
        
        .forgot-link {
            font-size: 0.875rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            color: #2563eb;
        }
        
        .login-button {
            width: 100%;
            padding: 0.875rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .login-button:hover {
            background: #2563eb;
        }
        
        .login-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .register-link {
            font-size: 0.875rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link:hover {
            color: #2563eb;
        }
        
        .copyright {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        @media (max-width: 640px) {
            .login-card {
                padding: 1.5rem;
                margin: 0 0.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        @php
            // Cek background image
            $bgPaths = [
                public_path('images/login-background.jpg'),
                storage_path('app/public/images/login-background.jpg'),
                'C:/xampp/htdocs/seumpamapointv2/storage/images/login-background.jpg',
            ];
            
            $backgroundImage = null;
            foreach ($bgPaths as $path) {
                if (file_exists($path)) {
                    if (strpos($path, 'public/') !== false) {
                        $backgroundImage = asset(str_replace(public_path(), '', $path));
                    } elseif (strpos($path, 'storage/app/public/') !== false) {
                        $backgroundImage = \Illuminate\Support\Facades\Storage::url('images/login-background.jpg');
                    } else {
                        // Encode langsung ke base64 untuk path absolut
                        $imageData = base64_encode(file_get_contents($path));
                        $mimeType = mime_content_type($path);
                        $backgroundImage = "data:{$mimeType};base64,{$imageData}";
                    }
                    break;
                }
            }
            
            if (!$backgroundImage) {
                $backgroundImage = 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';
            }
        @endphp
        
        <div class="login-background">
            <div class="login-background-image" 
                 style="background-image: url('{{ $backgroundImage }}');"></div>
            <div class="login-background-overlay"></div>
        </div>
        
        <div class="login-card">
            <div class="login-header">
                @if(config('filament.brand_logo'))
                    <img src="{{ asset(config('filament.brand_logo')) }}" 
                         alt="{{ config('app.name') }}" 
                         class="login-logo">
                @endif
                
                <h1 class="login-title">
                    {{ __('filament-panels::pages/auth/login.title') }}
                </h1>
                <p class="login-subtitle">
                    {{ __('Silakan masukkan kredensial Anda') }}
                </p>
            </div>
            
            <form wire:submit.prevent="authenticate" class="login-form">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">
                        {{ __('filament-panels::pages/auth/login.form.email.label') }}
                    </label>
                    <input type="email" 
                           name="email"
                           wire:model="data.email"
                           required 
                           autofocus
                           autocomplete="email"
                           class="form-input"
                           placeholder="nama@example.com">
                    @error('data.email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        {{ __('filament-panels::pages/auth/login.form.password.label') }}
                    </label>
                    <input type="password" 
                           name="password"
                           wire:model="data.password"
                           required 
                           autocomplete="current-password"
                           class="form-input"
                           placeholder="••••••••">
                    @error('data.password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-remember">
                    <label class="remember-label">
                        <input type="checkbox" 
                               name="remember"
                               wire:model="data.remember"
                               value="1">
                        <span>{{ __('filament-panels::pages/auth/login.form.remember.label') }}</span>
                    </label>
                    
                    @if(filament()->hasPasswordReset())
                        <a href="{{ filament()->getRequestPasswordResetUrl() }}" 
                           class="forgot-link">
                            {{ __('filament-panels::pages/auth/login.actions.request_password_reset.label') }}
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="login-button">
                    {{ __('filament-panels::pages/auth/login.form.actions.authenticate.label') }}
                </button>
                
                @if(filament()->hasRegistration())
                    <div class="login-footer">
                        <a href="{{ filament()->getRegistrationUrl() }}" 
                           class="register-link">
                            {{ __('filament-panels::pages/auth/login.actions.register.label') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
        
        <div class="copyright">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
    
    {{-- Filament Scripts --}}
    @filamentScripts
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus pada input email
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) {
                emailInput.focus();
            }
            
            // Handle form submission
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span>Memproses...</span>';
                    }
                });
            }
        });
    </script>
</body>
</html>