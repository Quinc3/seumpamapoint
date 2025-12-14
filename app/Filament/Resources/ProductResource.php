<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\ProductResource\Pages;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 4;

    // Tambahkan di class ProductResource
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
                Group::make([
                    Section::make('Product Information')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->prefix('IDR')
                                ->step(1)
                                ->minValue(0),

                            Forms\Components\TextInput::make('cost_price')
                                ->label('Cost Price')
                                ->numeric()
                                ->prefix('IDR')
                                ->step(1)
                                ->minValue(0)
                                ->nullable()
                                ->helperText('For profit calculation'),

                            Forms\Components\TextInput::make('stock')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->step(1),

                            Forms\Components\Toggle::make('is_active')
                                ->required()
                                ->default(true)
                                ->inline(false),

                            Forms\Components\Toggle::make('in_stock')
                                ->required()
                                ->default(true)
                                ->inline(false),
                        ])->columns(2),
                ])->columnSpan(2),

                Group::make([
                    // CATEGORY
                    Section::make('Product Classification')
                        ->schema([
                            Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->label('Category Name'),
                                    Forms\Components\Toggle::make('is_active')
                                        ->default(true)
                                        ->label('Active'),
                                ])
                                ->helperText('Choose product category'),

                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->directory('products')
                                ->maxSize(2048)
                                ->nullable()
                                ->columnSpanFull()
                                ->helperText('Max 2MB'),
                        ]),

                    Section::make('Product Ingredients')
                        ->schema([
                            Repeater::make('ingredient_items')
                                ->label('Ingredients (Optional)')
                                ->schema([
                                    Select::make('ingredient_id')
                                        ->label('Ingredient')
                                        ->options(
                                            \App\Models\Ingredient::where('is_active', true)
                                                ->orderBy('name')
                                                ->pluck('name', 'id')
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->nullable()
                                        ->helperText('Select an ingredient'),

                                    Forms\Components\TextInput::make('quantity')
                                        ->numeric()
                                        ->step(0.1)
                                        ->minValue(0.1)
                                        ->nullable()
                                        ->label('Quantity')
                                        ->suffix(function ($get) {
                                            $ingredientId = $get('ingredient_id');
                                            if ($ingredientId) {
                                                return \App\Models\Ingredient::find($ingredientId)?->unit ?? 'unit';
                                            }
                                            return 'unit';
                                        })
                                        ->helperText('Amount used per product'),
                                ])
                                ->columns(2)
                                ->defaultItems(0)
                                ->addActionLabel('Add Ingredient')
                                ->reorderable(false)
                                ->collapsible(false)
                                ->helperText('Leave empty if this product does not require ingredients'),
                        ])
                        ->collapsible(false),
                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('production_status_text')
                    ->label('Ingredients')
                    ->badge()
                    ->color(fn(Product $record) => $record->production_status_color)
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('in_stock')
                    ->label('In Stock')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Category'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),

                Tables\Filters\TernaryFilter::make('in_stock')
                    ->label('In Stock'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}