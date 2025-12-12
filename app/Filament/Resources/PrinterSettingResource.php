<?php

namespace App\Filament\Resources;

use App\Models\PrinterSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class PrinterSettingResource extends Resource
{
    protected static ?string $model = PrinterSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Printer Settings';
    protected static ?int $navigationSort = 101;

    // Tambahkan di class PrinterSettingResource
    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Auto Print Configuration')
                    ->schema([
                        Forms\Components\Toggle::make('auto_print')
                            ->label('Enable Auto Print')
                            ->helperText('Automatically print invoice when order is paid')
                            ->default(true)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Printer Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('printer_name')
                            ->label('Printer Name')
                            ->helperText('Name of your installed printer')
                            ->placeholder('e.g., Brother HL-T4000DW Printer')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('printer_connection')
                            ->label('Connection Type')
                            ->options([
                                'usb' => 'USB',
                                'network' => 'Network',
                                'bluetooth' => 'Bluetooth',
                            ])
                            ->default('usb')
                            ->required(),

                        Forms\Components\Select::make('paper_size')
                            ->label('Paper Size')
                            ->options([
                                '58mm' => '58mm Thermal',
                                '80mm' => '80mm Thermal',
                                'A4' => 'A4 Paper',
                            ])
                            ->default('80mm')
                            ->required()
                            ->helperText('Paper size for both PDF and thermal printing'),

                        Forms\Components\TextInput::make('copies')
                            ->label('Number of Copies')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(1)
                            ->required(),

                        Forms\Components\Toggle::make('test_mode')
                            ->label('Test Mode')
                            ->helperText('Test mode: simulate printing without actual print')
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Test Printer')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('test_simple')
                                ->label('Test Simple Print')
                                ->icon('heroicon-o-check')
                                ->color('success')
                                ->action(function () {
                                    $printService = app(\App\Services\ThermalPrintService::class);
                                    $result = $printService->testPrint('simple');

                                    if ($result['success']) {
                                        Notification::make()
                                            ->title('Test Print Simple Berhasil')
                                            ->body('Test print simple berhasil dikirim ke printer!')
                                            ->success()
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Test Print Gagal')
                                            ->body('Test print gagal: ' . $result['error'])
                                            ->danger()
                                            ->send();
                                    }
                                }),

                            Forms\Components\Actions\Action::make('test_full')
                                ->label('Test Full Receipt')
                                ->icon('heroicon-o-document')
                                ->color('primary')
                                ->action(function () {
                                    $printService = app(\App\Services\ThermalPrintService::class);
                                    $result = $printService->testPrint('full');

                                    if ($result['success']) {
                                        Notification::make()
                                            ->title('Test Print Full Berhasil')
                                            ->body('Test print full berhasil dikirim ke printer!')
                                            ->success()
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Test Print Gagal')
                                            ->body('Test print gagal: ' . $result['error'])
                                            ->danger()
                                            ->send();
                                    }
                                }),

                            Forms\Components\Actions\Action::make('list_printers')
                                ->label('List Printers')
                                ->icon('heroicon-o-list-bullet')
                                ->color('gray')
                                ->action(function () {
                                    $printService = app(\App\Services\ThermalPrintService::class);
                                    $result = $printService->getAvailablePrinters();

                                    if ($result['success']) {
                                        Notification::make()
                                            ->title('Printer List Berhasil Diambil')
                                            ->body('Cek logs untuk detail printer yang tersedia.')
                                            ->success()
                                            ->send();
                                        Log::info('Available Printers:', ['printers' => $result['printers']]);
                                    } else {
                                        Notification::make()
                                            ->title('Gagal Mengambil Printer List')
                                            ->body('Error: ' . $result['error'])
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ])->fullWidth(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('auto_print')
                    ->label('Auto Print')
                    ->boolean(),

                Tables\Columns\TextColumn::make('printer_name')
                    ->label('Printer Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('printer_connection')
                    ->label('Connection')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'usb' => 'success',
                        'network' => 'primary',
                        'bluetooth' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('paper_size')
                    ->label('Paper Size'),

                Tables\Columns\TextColumn::make('copies')
                    ->label('Copies'),

                Tables\Columns\IconColumn::make('test_mode')
                    ->label('Test Mode')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('test_print')
                    ->label('Test Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (PrinterSetting $record) {
                        $printService = app(\App\Services\ThermalPrintService::class);
                        $result = $printService->testPrint('simple');

                        if ($result['success']) {
                            Notification::make()
                                ->title('Test Print Berhasil')
                                ->body('Test print berhasil dikirim ke printer!')
                                ->success()
                                ->send();
                            Log::info('Test print dari table action berhasil');
                        } else {
                            Notification::make()
                                ->title('Test Print Gagal')
                                ->body('Test print gagal: ' . $result['error'])
                                ->danger()
                                ->send();
                            Log::error('Test print dari table action gagal: ' . $result['error']);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\PrinterSettingResource\Pages\ListPrinterSettings::route('/'),
            'create' => \App\Filament\Resources\PrinterSettingResource\Pages\CreatePrinterSetting::route('/create'),
            'edit' => \App\Filament\Resources\PrinterSettingResource\Pages\EditPrinterSetting::route('/{record}/edit'),
        ];
    }

}