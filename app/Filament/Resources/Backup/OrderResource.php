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
        $user = auth()->user();
        return $user && $user->hasRole('admin');
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
                        Section::make('Order Details')
                            ->schema([

                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->placeholder('Customer Name - Optional')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // Quick Add Product
                                Select::make('quick_add')
                                    ->label('Add Product')
                                    ->placeholder('Select product...')
                                    ->options(Product::where('stock', '>', 0)->pluck('name', 'id'))
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
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
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->options(Product::where('stock', '>', 0)->pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if (!$state)
                                                    return;

                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('price', $product->price);
                                                    $qty = $get('qty') ?? 1;
                                                    $set('subtotal', $product->price * $qty);
                                                    self::calculateTotals($set, $get);
                                                }
                                            })
                                            ->columnSpan(2),

                                        TextInput::make('qty')
                                            ->label('Qty')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $price = $get('price') ?? 0;
                                                $set('subtotal', $price * $state);
                                                self::calculateTotals($set, $get);
                                            }),

                                        TextInput::make('price')
                                            ->label('Price')
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->readonly()
                                            ->required(),

                                        TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->readonly()
                                            ->required(),
                                    ])
                                    ->columns(4)
                                    ->defaultItems(0)
                                    ->columnSpanFull()
                                    ->reorderable(false)
                                    ->createItemButtonLabel('Add Item Manually')

                                    ->loadStateFromRelationshipsUsing(function (Repeater $component, ?Order $record) {
                                        if ($record && $record->exists) {
                                            $orderItems = $record->orderDetails->map(function ($detail) {
                                                return [
                                                    'product_id' => $detail->product_id,
                                                    'qty' => $detail->qty,
                                                    'price' => $detail->price,
                                                    'subtotal' => $detail->subtotal,
                                                ];
                                            })->toArray();

                                            $component->state($orderItems);
                                        }
                                    }),
                            ])
                            ->columnSpan(2),

                        Section::make('Payment Information')
                            ->schema([
                                // Totals Display
                                Grid::make(1)
                                    ->schema([
                                        Placeholder::make('subtotal_display')
                                            ->label('Subtotal')
                                            ->content(function (Get $get) {
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
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->default(0)
                                                    ->suffix('%')
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        self::calculateTotals($set, $get);
                                                    }),

                                                Placeholder::make('discount_amount_display')
                                                    ->label('Discount Amount')
                                                    ->content(function (Get $get) {
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
                                            ->content(function (Get $get) {
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
                                            ->default('cash')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
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
                                                    ->content(function (Get $get) {
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
                                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
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
                                                            ->content(function (Get $get) {
                                                                $change = $get('cash_change') ?? 0;
                                                                return 'IDR ' . number_format($change, 0, ',', '.');
                                                            })
                                                            ->extraAttributes(function (Get $get) {
                                                                $change = $get('cash_change') ?? 0;
                                                                $color = $change >= 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold';
                                                                return ['class' => "text-md {$color}"];
                                                            }),
                                                    ]),
                                            ]),
                                    ])
                                    ->visible(fn(Get $get) => $get('payment_method') === 'cash')
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
                                    ->visible(fn(Get $get) => $get('payment_method') === 'cash'),

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

    protected static function afterCreate(Order $order, array $data): void
    {

        \Log::info("🎯 AFTER CREATE CALLED for order: " . $order->id);
        \Log::info("📦 Order data:", $data);

        self::saveOrderDetails($order, $data);

        // Auto print jika status paid
        if ($order->payment_status === 'paid') {
            \Log::info("🎯 NEW ORDER CREATED AS PAID - Triggering auto print for order: " . $order->id);
            event(new OrderPaid($order));
        }

        // Auto open cash drawer jika cash payment
        if (($data['payment_method'] ?? null) === 'cash' && ($data['open_cash_drawer'] ?? true)) {
            \Log::info("💰 OPENING CASH DRAWER for order: " . $order->id);
            self::openCashDrawer();
        }
    }


    protected static function afterSave(Order $order, array $data): void
    {
        self::saveOrderDetails($order, $data);

        // Auto print jika status berubah ke paid
        $originalStatus = $order->getOriginal('payment_status');
        $newStatus = $data['payment_status'] ?? $order->payment_status;

        if ($originalStatus !== 'paid' && $newStatus === 'paid') {
            \Log::info("🎯 PAYMENT STATUS CHANGED TO PAID - Triggering auto print for order: " . $order->id);
            event(new OrderPaid($order));
        }

        // Auto open cash drawer jika cash payment
        if (($data['payment_method'] ?? null) === 'cash' && ($data['open_cash_drawer'] ?? true)) {
            \Log::info("💰 OPENING CASH DRAWER for order: " . $order->id);
            self::openCashDrawer();
        }
    }

    protected static function saveOrderDetails(Order $order, array $data): void
    {
        $order->orderDetails()->delete();

        \Log::info("💾 SAVING ORDER DETAILS - Data:", [
            'order_items' => $data['order_items'] ?? 'NO ITEMS',
            'count' => count($data['order_items'] ?? [])
        ]);

        if (isset($data['order_items']) && is_array($data['order_items'])) {
            foreach ($data['order_items'] as $item) {
                if (!empty($item['product_id']) && !empty($item['qty'])) {
                    \Log::info("➕ Creating order detail:", $item);

                    // Create order detail
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // DECREMENT STOCK dengan debug lengkap
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        \Log::info("📦 BEFORE DECREMENT - Product: " . $product->name . ", Stock: " . $product->stock . ", Qty to decrement: " . $item['qty']);

                        // DECREMENT STOCK
                        $product->decrement('stock', $item['qty']);

                        // RELOAD product untuk get updated stock
                        $product->refresh();

                        \Log::info("📦 AFTER DECREMENT - Product: " . $product->name . ", Stock: " . $product->stock);

                        // Update in_stock status
                        if ($product->stock <= 0) {
                            $product->update(['in_stock' => false]);
                            \Log::info("🔄 Product marked as out of stock: " . $product->name);
                        }
                    } else {
                        \Log::error("❌ Product not found for ID: " . $item['product_id']);
                    }
                }
            }
        }

        \Log::info("✅ Order details saved for order: " . $order->id, [
            'items_count' => $order->orderDetails()->count(),
            'items' => $order->orderDetails->map(fn($d) => [
                'product' => $d->product->name ?? 'Unknown',
                'qty' => $d->qty,
                'subtotal' => $d->subtotal
            ])->toArray()
        ]);
    }

    protected static function beforeCreate(array $data): array
    {
        Log::info("🎯 BEFORE CREATE - Saving order_items to session", [
            'order_items_count' => count($data['order_items'] ?? []),
            'order_items' => $data['order_items'] ?? []
        ]);

        // Simpan order_items ke session untuk diakses oleh observer
        session(['current_order_items' => $data['order_items'] ?? []]);

        return $data;
    }

    public function handle(OrderPaid $event)
    {
        \Log::info("🎯 AutoPrintInvoice DIPANGGIL untuk order: " . $event->order->id);

        // Load relationship dengan product
        $event->order->load(['orderDetails.product']);

        \Log::info("📦 Data order sebelum print:", [
            'details_count' => $event->order->orderDetails->count(),
            'items' => $event->order->orderDetails->map(fn($d) => [
                'product' => $d->product->name ?? 'NO PRODUCT',
                'qty' => $d->qty,
                'subtotal' => $d->subtotal
            ])->toArray()
        ]);

        $printerSettings = \App\Models\PrinterSetting::getSettings();

    }

    protected static function openCashDrawer(): void
    {
        try {
            // ESC/POS Command untuk buka cash drawer
            $printerIp = config('printing.cash_drawer_ip', '192.168.1.100');
            $printerPort = config('printing.cash_drawer_port', 9100);

            $command = chr(27) . chr(112) . chr(0) . chr(100) . chr(100); // ESC/POS command

            // Kirim command ke printer via socket
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket !== false) {
                $result = socket_connect($socket, $printerIp, $printerPort);
                if ($result !== false) {
                    socket_write($socket, $command, strlen($command));
                    socket_close($socket);
                    \Log::info("✅ Cash drawer opened successfully");
                } else {
                    \Log::error("❌ Failed to connect to cash drawer");
                }
            }
        } catch (\Exception $e) {
            \Log::error("❌ Cash drawer error: " . $e->getMessage());
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
            ->modifyQueryUsing(fn(Builder $query) => $query->whereDate('created_at', today()))
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
                        foreach ($record->orderDetails as $orderDetail) {
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

                            foreach ($record->orderDetails as $orderDetail) {
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