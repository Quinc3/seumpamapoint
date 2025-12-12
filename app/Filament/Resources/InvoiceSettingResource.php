<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceSettingResource\Pages;
use App\Models\InvoiceSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class InvoiceSettingResource extends Resource
{
    protected static ?string $model = InvoiceSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Invoice Settings';
    protected static ?int $navigationSort = 100;

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
                Forms\Components\Section::make('Company Information')
                    ->description('Your company details that will appear on invoices')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Your company name'),

                        Forms\Components\Textarea::make('company_address')
                            ->label('Company Address')
                            ->rows(3)
                            ->placeholder('Your company address...'),

                        Forms\Components\TextInput::make('company_phone')
                            ->label('Phone Number')
                            ->placeholder('Your company number'),

                        Forms\Components\TextInput::make('company_email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('hello@yourcompany.com'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Content Settings')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_title')
                            ->label('Invoice Title')
                            ->required()
                            ->default('INVOICE')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('footer_text')
                            ->label('Footer Text')
                            ->rows(2)
                            ->placeholder('Thank you for your business!'),

                        Forms\Components\Textarea::make('terms_conditions')
                            ->label('Terms & Conditions')
                            ->rows(3)
                            ->placeholder('Your terms and conditions...'),
                    ])
                    ->columns(1),

                // TAMBAH SECTION BARU UNTUK PAYMENT DISPLAY SETTINGS
                Forms\Components\Section::make('Payment Display Settings')
                    ->description('Configure how payment information appears on invoices')
                    ->schema([
                        Forms\Components\Toggle::make('show_cash_details')
                            ->label('Show Cash Details')
                            ->default(true)
                            ->helperText('Display cash received and change on invoices'),

                        Forms\Components\Toggle::make('show_payment_summary')
                            ->label('Show Payment Summary')
                            ->default(true)
                            ->helperText('Display payment method and status summary'),

                        Forms\Components\Toggle::make('auto_calculate_change')
                            ->label('Auto Calculate Change')
                            ->default(true)
                            ->helperText('Automatically calculate change when cash received is entered'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Appearance')
                    ->schema([
                        Forms\Components\Toggle::make('show_logo')
                            ->label('Show Company Logo')
                            ->default(true)
                            ->helperText('Display logo on invoices'),

                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Company Logo')
                            ->image()
                            ->directory('invoice-settings')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Recommended: 200x200px PNG or JPG'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('invoice_title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('show_logo')
                    ->boolean()
                    ->label('Logo'),

                Tables\Columns\IconColumn::make('show_cash_details')
                    ->boolean()
                    ->label('Cash Details'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Last Updated'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoiceSettings::route('/'),
        ];
    }
}