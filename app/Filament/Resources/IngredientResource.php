<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Ingredient;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use App\Filament\Resources\IngredientResource\Pages;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 3;

    // Tambahkan di class IngredientResource
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
                Section::make('Ingredient Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Ingredient Name'),

                        Forms\Components\TextInput::make('unit')
                            ->required()
                            ->maxLength(50)
                            ->label('Unit')
                            ->placeholder('ml, gram, pcs, sachet, etc')
                            ->helperText('Measurement unit for this ingredient'),

                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->default(0)
                            ->step(0.01) // TAMBAHKAN INI
                            ->suffix(fn($get) => $get('unit') ?: 'unit')
                            ->label('Current Stock'),

                        Forms\Components\TextInput::make('min_stock')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->suffix(fn($get) => $get('unit') ?: 'unit')
                            ->label('Minimum Stock')
                            ->helperText('Alert when stock reaches this level'),

                        Forms\Components\TextInput::make('cost_per_unit')
                            ->numeric()
                            ->default(0)
                            ->prefix('IDR')
                            ->step(0.01)
                            ->label('Cost per Unit'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn(Ingredient $record) => $record->unit),

                Tables\Columns\TextColumn::make('stock')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->description(fn(Ingredient $record) => $record->unit),

                Tables\Columns\TextColumn::make('min_stock')
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->description(fn(Ingredient $record) => $record->unit),

                Tables\Columns\TextColumn::make('cost_per_unit')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_status')
                    ->label('Stock Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'out_of_stock' => 'Out of Stock',
                        'low_stock' => 'Low Stock',
                        'in_stock' => 'In Stock',
                        default => 'Unknown'
                    })
                    ->color(fn($state) => match ($state) {
                        'out_of_stock' => 'danger',
                        'low_stock' => 'warning',
                        'in_stock' => 'success',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Used In')
                    ->counts('products')
                    ->formatStateUsing(fn($state) => $state . ' products')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stock_status')
                    ->options([
                        'out_of_stock' => 'Out of Stock',
                        'low_stock' => 'Low Stock',
                        'in_stock' => 'In Stock',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'view' => Pages\ViewIngredient::route('/{record}'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}