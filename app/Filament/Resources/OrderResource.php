<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use App\Events\OrderPaid;
use Filament\Tables\Table;
use App\Models\OrderDetail;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    // Method untuk cek permission create
    public static function canCreate(): bool
    {
        return auth()->check();
    }

    // Method untuk cek permission edit
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }

    // Method untuk cek permission delete
    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Hidden::make('user_id')
                            ->default(fn() => auth()->id())
                            ->required()
                            ->dehydrated(true),
                        Section::make('Order Details')
                            ->schema([

                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->placeholder('Customer Name - Optional')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // Category selector (choose before adding products)
                                Select::make('category_id')
                                    ->label('Category')
                                    ->placeholder('Select category to filter products...')
                                    ->options(Category::pluck('name', 'id'))
                                    ->searchable()
                                    ->reactive(),

                                // Quick Add Product
                                Select::make('quick_add')
                                    ->label('Add Product')
                                    ->placeholder('Select product...')
                                    ->searchable()
                                    ->reactive()
                                    ->options(function (callable $get) {
                                        $query = Product::where('stock', '>', 0);
                                        $category = $get('category_id');

                                        if ($category) {
                                            $query->where('category_id', $category);
                                        }

                                        return $query->pluck('name', 'id');
                                    })
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if (!$state)
                                            return;

                                        $product = Product::find($state);
                                        if (!$product)
                                            return;

                                        $currentItems = $get('order_items') ?? [];

                                        // CEK APAKAH PRODUK SUDAH ADA DI CART
                                        $existingItemIndex = null;
                                        foreach ($currentItems as $index => $item) {
                                            if ($item['product_id'] == $state) {
                                                $existingItemIndex = $index;
                                                break;
                                            }
                                        }

                                        if ($existingItemIndex !== null) {
                                            // PRODUK SUDAH ADA - TAMBAH QTY
                                            $currentItems[$existingItemIndex]['qty'] += 1;
                                            $currentItems[$existingItemIndex]['subtotal'] = $currentItems[$existingItemIndex]['price'] * $currentItems[$existingItemIndex]['qty'];
                                        } else {
                                            // PRODUK BARU - BUAT ITEM BARU
                                            $newItem = [
                                                'product_id' => $product->id,
                                                'product_name' => $product->name,
                                                'qty' => 1,
                                                'price' => $product->price,
                                                'subtotal' => $product->price,
                                            ];
                                            $currentItems[] = $newItem;
                                        }

                                        $set('order_items', $currentItems);
                                        $set('quick_add', null);

                                        self::calculateTotals($set, $get);
                                    })
                                    ->columnSpanFull(),

                                // Order Items
                                Repeater::make('order_items')
                                    ->label('Order Items')
                                    ->statePath('order_items')
                                    ->dehydrated(true)
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->required()
                                            ->reactive()
                                            ->options(function (callable $get) {
                                                $query = Product::where('stock', '>', 0);
                                                $category = $get('category_id');

                                                if ($category) {
                                                    $query->where('category_id', $category);
                                                }

                                                return $query->pluck('name', 'id');
                                            })
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                if (!$state) {
                                                    return;
                                                }

                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('price', $product->price);
                                                    $qty = $get('qty') ?? 1;
                                                    $set('subtotal', $product->price * $qty);
                                                }
                                            }),

                                        TextInput::make('qty')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $price = $get('price') ?? 0;
                                                $set('subtotal', $price * $state);
                                            }),

                                        TextInput::make('price')
                                            ->numeric()
                                            ->readonly(),

                                        TextInput::make('subtotal')
                                            ->numeric()
                                            ->readonly(),
                                    ])
                                    ->columns(4)

                                    ->defaultItems(0)
                                    ->reorderable(false)
                                    ->createItemButtonLabel('Add Item')

                                    ->loadStateFromRelationshipsUsing(function ($component, $record) {
                                        if (!$record)
                                            return;

                                        $component->state(
                                            $record->items->map(fn($item) => [
                                                'product_id' => $item->product_id,
                                                'qty' => $item->qty,
                                                'price' => $item->price,
                                                'subtotal' => $item->subtotal,
                                            ])->toArray()
                                        );
                                    })
                            ])

                            ->columnSpan(2),

                        Section::make('Payment Information')
                            ->schema([
                                // Totals Display
                                Grid::make(1)
                                    ->schema([
                                        Placeholder::make('subtotal_display')
                                            ->label('Subtotal')
                                            ->content(function ($get) {
                                                $items = $get('order_items') ?? [];
                                                $subtotal = collect($items)->sum('subtotal');
                                                return 'IDR ' . number_format($subtotal, 0, ',', '.');
                                            })
                                            ->extraAttributes(['class' => 'text-sm font-medium']),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('discount')
                                                    ->label('Discount %')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->default(0)
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, $set, $get) {
                                                        // Paksa nilai antara 0–100
                                                        $value = (int) $state;

                                                        if ($value < 0) {
                                                            $value = 0;
                                                        }

                                                        if ($value > 100) {
                                                            $value = 100;
                                                        }

                                                        $set('discount', $value);

                                                        self::calculateTotals($set, $get);
                                                    })
                                                ,

                                                Placeholder::make('discount_amount_display')
                                                    ->label('Discount Amount')
                                                    ->content(function ($get) {
                                                        $items = $get('order_items') ?? [];
                                                        $subtotal = collect($items)->sum('subtotal');
                                                        $discount = $get('discount') ?? 0;
                                                        $discountAmount = $subtotal * $discount / 100;
                                                        return 'IDR ' . number_format($discountAmount, 0, ',', '.');
                                                    })
                                                    ->extraAttributes(['class' => 'text-sm']),
                                            ]),

                                        Placeholder::make('total_display')
                                            ->label('TOTAL')
                                            ->content(function ($get) {
                                                $items = $get('order_items') ?? [];
                                                $subtotal = collect($items)->sum('subtotal');
                                                $discount = $get('discount') ?? 0;
                                                $discountAmount = $subtotal * $discount / 100;
                                                $total = $subtotal - $discountAmount;
                                                return 'IDR ' . number_format($total, 0, ',', '.');
                                            })
                                            ->extraAttributes(['class' => 'text-lg font-bold text-primary border-t pt-2']),
                                    ]),

                                // Payment Details
                                Grid::make(2)
                                    ->schema([
                                        Select::make('payment_method')
                                            ->options([
                                                'cash' => 'Cash',
                                                'qris' => 'QRIS',
                                                'debit' => 'Debit',
                                            ])
                                            ->default('qris')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                if ($state !== 'cash') {
                                                    $set('cash_received', null);
                                                    $set('cash_change', 0);
                                                    $set('show_calculator', false);
                                                }
                                            }),

                                        Select::make('payment_status')
                                            ->options([
                                                'paid' => 'Paid',
                                                'unpaid' => 'Unpaid',
                                                'failed' => 'Failed',
                                            ])
                                            ->default('unpaid')
                                            ->required(),
                                    ]),

                                // KALKULATOR
                                Section::make('Calculator')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                // Column 1: Amount to Pay
                                                Placeholder::make('total_payment_display')
                                                    ->label('Amount to Pay')
                                                    ->content(function ($get) {
                                                        $items = $get('order_items') ?? [];
                                                        $subtotal = collect($items)->sum('subtotal');
                                                        $discount = $get('discount') ?? 0;
                                                        $discountAmount = $subtotal * $discount / 100;
                                                        $total = $subtotal - $discountAmount;
                                                        return 'IDR ' . number_format($total, 0, ',', '.');
                                                    })
                                                    ->extraAttributes(['class' => 'text-md font-bold text-primary']),

                                                // Column 2: Cash & Change
                                                Grid::make(1)
                                                    ->schema([
                                                        TextInput::make('cash_received')
                                                            ->label('Cash Received')
                                                            ->numeric()
                                                            ->prefix('IDR')
                                                            ->reactive()
                                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                                $items = $get('order_items') ?? [];
                                                                $subtotal = collect($items)->sum('subtotal');
                                                                $discount = $get('discount') ?? 0;
                                                                $discountAmount = $subtotal * $discount / 100;
                                                                $total = $subtotal - $discountAmount;

                                                                $cashReceived = floatval($state) ?? 0;
                                                                $change = $cashReceived - $total;

                                                                $set('cash_change', $change > 0 ? $change : 0);
                                                            }),

                                                        Placeholder::make('cash_change_display')
                                                            ->label('Change')
                                                            ->content(function ($get) {
                                                                $change = $get('cash_change') ?? 0;
                                                                return 'IDR ' . number_format($change, 0, ',', '.');
                                                            })
                                                            ->extraAttributes(function ($get) {
                                                                $change = $get('cash_change') ?? 0;
                                                                $color = $change >= 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold';
                                                                return ['class' => "text-md {$color}"];
                                                            }),
                                                    ]),
                                            ]),
                                    ])
                                    ->visible(fn($get) => $get('payment_method') === 'cash')
                                    ->compact()
                                    ->collapsible()
                                    ->collapsed(false),

                                Select::make('status')
                                    ->options([
                                        'new' => 'New',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('new')
                                    ->required()
                                    ->columnSpanFull(),

                                // AUTO OPEN CASH DRAWER SETTING
                                Toggle::make('open_cash_drawer')
                                    ->label('Open Cash Drawer on save')
                                    ->default(true)
                                    ->visible(fn($get) => $get('payment_method') === 'cash'),

                                // Hidden fields for database
                                Hidden::make('total_price')
                                    ->reactive(),
                                Hidden::make('discount_amount')
                                    ->reactive(),
                                Hidden::make('total_payment')
                                    ->reactive(),
                                Hidden::make('cash_change'),
                                Hidden::make('show_calculator')
                                    ->default(false),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }




    protected static function calculateTotals(Set $set, Get $get): void
    {
        $items = $get('order_items') ?? [];
        $subtotal = collect($items)->sum('subtotal');
        $discount = floatval($get('discount') ?? 0);
        $discountAmount = $subtotal * $discount / 100;
        $totalPayment = max(0, $subtotal - $discountAmount);

        $set('total_price', $subtotal);
        $set('discount_amount', $discountAmount);
        $set('total_payment', $totalPayment);
    }


    protected static function syncitems(Order $order, array $data): void
    {
        // 1. BALIKIN STOK LAMA
        foreach ($order->items as $detail) {
            if ($detail->product) {
                $detail->product->increment('stock', $detail->qty);
                $detail->product->update(['in_stock' => true]);
            }
        }

        // 2. HAPUS DETAIL LAMA
        $order->items()->delete();

        // 3. SIMPAN DETAIL BARU + KURANGI STOK
        foreach ($data['order_items'] ?? [] as $item) {
            if (empty($item['product_id']) || empty($item['qty'])) {
                continue;
            }

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            $product = Product::find($item['product_id']);
            if ($product) {
                $product->decrement('stock', $item['qty']);

                if ($product->stock <= 0) {
                    $product->update(['in_stock' => false]);
                }
            }
        }
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M H:i')
                    ->sortable(),

                TextColumn::make('total_payment')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->rules(['required'])
                    ->selectablePlaceholder(false),

                SelectColumn::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'failed' => 'Failed',
                    ])
                    ->rules(['required'])
                    ->selectablePlaceholder(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('From Date')
                            ->default(now()->subDays(30)),
                        DatePicker::make('created_until')
                            ->label('Until Date')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Filter::make('today')
                    ->label('Today')
                    ->query(fn(Builder $query): Builder => $query->whereDate('created_at', today()))
                    ->default(),

                Filter::make('yesterday')
                    ->label('Yesterday')
                    ->query(fn(Builder $query): Builder => $query->whereDate('created_at', today()->subDay())),

                Filter::make('last_7_days')
                    ->label('Last 7 Days')
                    ->query(fn(Builder $query): Builder => $query->whereBetween('created_at', [today()->subDays(6), today()])),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'failed' => 'Failed',
                    ]),
            ])
            ->headerActions([
                Action::make('downloadReport')
                    ->label('Download Report')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->form([
                        Select::make('report_type')
                            ->label('Report Type')
                            ->options([
                                'daily' => 'Daily Report',
                                'monthly' => 'Monthly Report',
                                'custom' => 'Custom Date Range',
                                'filtered' => 'Current Filter Results',
                            ])
                            ->required()
                            ->reactive(),

                        DatePicker::make('date')
                            ->label('Date')
                            ->visible(fn($get) => $get('report_type') === 'daily'),

                        Select::make('month')
                            ->label('Month')
                            ->options([
                                '1' => 'January',
                                '2' => 'February',
                                '3' => 'March',
                                '4' => 'April',
                                '5' => 'May',
                                '6' => 'June',
                                '7' => 'July',
                                '8' => 'August',
                                '9' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December',
                            ])
                            ->default(now()->month)
                            ->visible(fn($get) => $get('report_type') === 'monthly'),

                        Select::make('year')
                            ->label('Year')
                            ->options([
                                '2023' => '2023',
                                '2024' => '2024',
                                '2025' => '2025',
                            ])
                            ->default(now()->year)
                            ->visible(fn($get) => $get('report_type') === 'monthly'),

                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->visible(fn($get) => $get('report_type') === 'custom'),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->visible(fn($get) => $get('report_type') === 'custom'),

                        Select::make('report_category_id')
                            ->label('Category')
                            ->placeholder('Filter by category')
                            ->options(Category::pluck('name', 'id'))
                            ->searchable()
                            ->reactive(),

                        Select::make('report_product_id')
                            ->label('Product')
                            ->placeholder('Filter by product')
                            ->searchable()
                            ->reactive()
                            ->options(function (callable $get) {
                                $query = Product::query();
                                $category = $get('report_category_id');

                                if ($category) {
                                    $query->where('category_id', $category);
                                }

                                return $query->pluck('name', 'id');
                            }),

                        Select::make('format')
                            ->label('Format')
                            ->options([
                                'pdf' => 'PDF',
                                'excel' => 'Excel',
                            ])
                            ->default('pdf'),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('download.report', $data);
                    }),
            ])
            ->actions([
                // ACTION UNTUK CANCEL ORDER
                Action::make('cancel')
                    ->label('Cancel Order')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Order')
                    ->modalDescription('Are you sure you want to cancel this order? This will return all product stocks and ingredients.')
                    ->action(function (Order $record) {
                        \Log::info("🎯 CANCEL ACTION TRIGGERED - Order: " . $record->id);

                        // Update status ke cancelled
                        $record->update(['status' => 'cancelled']);

                        // Return stok produk dan bahan baku
                        foreach ($record->items()->get() as $orderDetail) {
                            $product = $orderDetail->product;

                            if ($product) {
                                // Return product stock
                                $product->increment('stock', $orderDetail->qty);

                                // Update in_stock status
                                if ($product->stock > 0) {
                                    $product->update(['in_stock' => true]);
                                }

                                // Return ingredient stock
                                if ($product->usesIngredients()) {
                                    foreach ($product->ingredients as $ingredient) {
                                        $quantityToReturn = $ingredient->pivot->quantity * $orderDetail->qty;
                                        $ingredient->increment('stock', $quantityToReturn);

                                        \Log::info("🔄 Ingredient returned: " . $ingredient->name .
                                            " +" . $quantityToReturn . $ingredient->unit);
                                    }
                                }

                                \Log::info("📦 Product stock returned: " . $product->name .
                                    " +" . $orderDetail->qty);
                            }
                        }

                        \Log::info("✅ ORDER CANCELLED SUCCESSFULLY - Order: " . $record->id);
                    })
                    ->visible(fn(Order $record) => $record->status !== 'cancelled'), // Hanya tampil jika belum cancelled

                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->url(fn(Order $record) => route('download.invoice', $record))
                    ->openUrlInNewTab(),


                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('downloadInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => route('download.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('cancel')
                    ->label('Cancel Selected Orders')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            \Log::info("🎯 BULK CANCEL - Order: " . $record->id);

                            $record->update(['status' => 'cancelled']);

                            foreach ($record->items as $orderDetail) {
                                $product = $orderDetail->product;

                                if ($product) {
                                    $product->increment('stock', $orderDetail->qty);

                                    if ($product->stock > 0) {
                                        $product->update(['in_stock' => true]);
                                    }

                                    if ($product->usesIngredients()) {
                                        foreach ($product->ingredients as $ingredient) {
                                            $quantityToReturn = $ingredient->pivot->quantity * $orderDetail->qty;
                                            $ingredient->increment('stock', $quantityToReturn);
                                        }
                                    }
                                }
                            }
                        }

                        \Log::info("✅ BULK CANCELLATION COMPLETED - " . count($records) . " orders cancelled");
                    })
                    ->deselectRecordsAfterCompletion(),

                // Bulk change status to any chosen status
                Tables\Actions\BulkAction::make('change_status')
                    ->label('Change Status')
                    ->form([
                        Select::make('status')
                            ->label('New Status')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function ($records, $data) {
                        $newStatus = $data['status'] ?? null;
                        if (!$newStatus) return;

                        foreach ($records as $record) {
                            // If cancelling, return stocks and ingredients similar to cancel action
                            if ($newStatus === 'cancelled') {
                                $record->update(['status' => 'cancelled']);

                                foreach ($record->items as $orderDetail) {
                                    $product = $orderDetail->product;

                                    if ($product) {
                                        $product->increment('stock', $orderDetail->qty);

                                        if ($product->stock > 0) {
                                            $product->update(['in_stock' => true]);
                                        }

                                        if ($product->usesIngredients()) {
                                            foreach ($product->ingredients as $ingredient) {
                                                $quantityToReturn = $ingredient->pivot->quantity * $orderDetail->qty;
                                                $ingredient->increment('stock', $quantityToReturn);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $record->update(['status' => $newStatus]);
                            }
                        }
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}