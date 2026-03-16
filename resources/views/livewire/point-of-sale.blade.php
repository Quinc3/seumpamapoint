{{-- resources/views/livewire/point-of-sale.blade.php --}}
<div class="h-screen bg-gray-100 p-4">
    <div class="grid grid-cols-2 gap-4 h-full">
        <!-- LEFT: PRODUCT GRID WITH PHOTOS -->
        <div class="col-span-1 bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-4">Products</h2>
            
            <!-- Product Grid with Photos -->
            <div class="grid grid-cols-2 gap-3 max-h-[calc(100vh-100px)] overflow-y-auto">
                @foreach($products as $product)
                <button 
                    wire:click="addToCart({{ $product->id }})"
                    class="p-3 border border-gray-200 rounded-lg text-center hover:bg-blue-50 hover:border-blue-300 transition-all duration-200"
                    wire:key="product-{{ $product->id }}"
                >
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-16 object-cover rounded mb-2">
                    @else
                        <div class="w-full h-16 bg-gray-200 rounded mb-2 flex items-center justify-center">
                            <span class="text-gray-500 text-xs">No Image</span>
                        </div>
                    @endif
                    <div class="font-semibold text-sm text-gray-800">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500">Stock: {{ $product->stock }}</div>
                    <div class="font-bold text-green-600 text-sm">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </button>
                @endforeach
                
                @if($products->isEmpty())
                <div class="col-span-2 text-center py-8 text-gray-500">
                    No products found
                </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: CUSTOMER INFO, CATEGORIES, ITEMS COUNT, PAYMENT -->
        <div class="col-span-1 bg-white rounded-lg shadow p-4 flex flex-col">
            <!-- Customer Info -->
            <div class="mb-4">
                <h3 class="text-md font-bold mb-2">Customer Information</h3>
                <input 
                    type="text" 
                    wire:model="customerName"
                    placeholder="Customer Name"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm mb-2"
                >
                <select wire:model="orderType" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <option value="dine_in">Dine In</option>
                    <option value="takeaway">Takeaway</option>
                    <option value="delivery">Delivery</option>
                </select>
                @if($orderType === 'dine_in')
                <input 
                    type="text" 
                    wire:model="tableNumber"
                    placeholder="Table Number"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm mt-2"
                >
                @endif
            </div>

            <!-- Categories Filter -->
            <div class="mb-4">
                <h3 class="text-md font-bold mb-2">Categories</h3>
                <div class="flex flex-wrap gap-2">
                    <button 
                        wire:click="$set('selectedCategoryId', null)"
                        class="px-3 py-1 text-xs rounded {{ !$selectedCategoryId ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}"
                    >
                        All
                    </button>
                    @foreach($categories as $category)
                    <button 
                        wire:click="$set('selectedCategoryId', {{ $category->id }})"
                        class="px-3 py-1 text-xs rounded {{ $selectedCategoryId == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}"
                    >
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Search -->
            <div class="mb-4">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search products..."
                    class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                >
            </div>

            <!-- Items Count -->
            <div class="mb-4">
                <h3 class="text-md font-bold mb-2">Items in Cart: {{ count($cart) }}</h3>
                <div class="space-y-1 max-h-32 overflow-y-auto">
                    @forelse($cart as $index => $item)
                    <div class="flex justify-between items-center text-sm">
                        <span>{{ $item['product_name'] }} ({{ $item['qty'] }})</span>
                        <span>IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="text-gray-500 text-sm">No items</div>
                    @endforelse
                </div>
            </div>

            <!-- Payment Information -->
            <div class="mt-auto">
                <h3 class="text-md font-bold mb-2">Payment Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal:</span>
                        <span>IDR {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Discount ({{ $discount }}%):</span>
                        <span>- IDR {{ number_format($total * ($discount / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total:</span>
                        <span>IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <select wire:model="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Debit Card</option>
                        <option value="credit">Credit Card</option>
                    </select>

                    @if($paymentMethod === 'cash')
                    <input 
                        type="number" 
                        wire:model.live="cashReceived"
                        placeholder="Cash Received"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                    >
                    @if($cashChange > 0)
                    <div class="text-green-600 text-sm">
                        Change: IDR {{ number_format($cashChange, 0, ',', '.') }}
                    </div>
                    @endif
                    @endif

                    <input 
                        type="number" 
                        wire:model="discount"
                        placeholder="Discount %"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                    >
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 space-y-2">
                    <button 
                        wire:click="showSummary"
                        class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
                        {{ empty($cart) ? 'disabled' : '' }}
                    >
                        Review Order
                    </button>
                    <button 
                        wire:click="processOrder"
                        class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600"
                        {{ empty($cart) ? 'disabled' : '' }}
                    >
                        Process Order
                    </button>
                </div>
            </div>
        </div>
                            <button 
                                wire:click="updateQuantity({{ $index }}, {{ $item['qty'] + 1 }})"
                                class="w-6 h-6 bg-gray-200 rounded text-xs flex items-center justify-center"
                            >+</button>
                            <button 
                                wire:click="removeFromCart({{ $index }})"
                                class="text-red-500 text-xs ml-2 flex items-center justify-center w-4 h-4"
                            >×</button>
                        </div>
                    </div>
                    <div class="text-sm font-semibold">
                        IDR {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">Cart is empty</div>
                @endforelse
            </div>

            <!-- Order Details Form -->
            <div class="space-y-3 border-t pt-4">
                <!-- Customer & Order Type -->
                <div>
                    <label class="block text-sm font-medium mb-1">Customer Name</label>
                    <input 
                        type="text" 
                        wire:model="customerName"
                        placeholder="Walk-in Customer"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                    >
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium mb-1">Order Type</label>
                        <select wire:model="orderType" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <option value="dine_in">Dine In</option>
                            <option value="takeaway">Takeaway</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Table No.</label>
                        <input 
                            type="text" 
                            wire:model="tableNumber"
                            placeholder="Table #"
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                            {{ $orderType !== 'dine_in' ? 'disabled' : '' }}
                        >
                    </div>
                </div>

                <!-- Totals -->
                <div class="space-y-2 bg-gray-50 p-3 rounded">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal:</span>
                        <span>IDR {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span>Discount ({{ $discount }}%):</span>
                        <span>- IDR {{ number_format($total * ($discount / 100), 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between font-bold border-t pt-2">
                        <span>Total:</span>
                        <span>IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment -->
                <div>
                    <label class="block text-sm font-medium mb-1">Payment Method</label>
                    <select wire:model="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Debit Card</option>
                        <option value="credit">Credit Card</option>
                    </select>
                </div>

                @if($paymentMethod === 'cash')
                <div>
                    <label class="block text-sm font-medium mb-1">Cash Received</label>
                    <input 
                        type="number" 
                        wire:model.live="cashReceived"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                    >
                    @if($cashChange > 0)
                    <div class="text-green-600 text-sm mt-1">
                        Change: IDR {{ number_format($cashChange, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
                @endif

                <!-- Discount -->
                <div>
                    <label class="block text-sm font-medium mb-1">Discount (%)</label>
                    <input 
                        type="number" 
                        wire:model.live="discount"
                        min="0" 
                        max="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm"
                    >
                </div>

                <!-- Process Button -->
                <button 
                    wire:click="processOrder"
                    class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                    {{ empty($cart) ? 'disabled' : '' }}
                >
                    PROCESS ORDER
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    @if($showSummaryModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4">Order Summary</h3>
            
            <div class="mb-4">
                <p><strong>Customer:</strong> {{ $customerName ?: 'Walk-in Customer' }}</p>
                <p><strong>Order Type:</strong> {{ ucfirst(str_replace('_', ' ', $orderType)) }}</p>
                @if($orderType === 'dine_in' && $tableNumber)
                <p><strong>Table:</strong> {{ $tableNumber }}</p>
                @endif
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Items:</h4>
                <div class="space-y-1 max-h-32 overflow-y-auto">
                    @foreach($cart as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item['product_name'] }} x{{ $item['qty'] }}</span>
                        <span>IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span>Subtotal:</span>
                    <span>IDR {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span>Discount ({{ $discount }}%):</span>
                    <span>- IDR {{ number_format($total * ($discount / 100), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span>IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span>Payment:</span>
                    <span>{{ ucfirst($paymentMethod) }}</span>
                </div>
            </div>

            <div class="flex space-x-2 mt-6">
                <button 
                    wire:click="closeSummary"
                    class="flex-1 bg-gray-500 text-white py-2 rounded hover:bg-gray-600"
                >
                    Close
                </button>
                <button 
                    wire:click="printInvoice"
                    class="flex-1 bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
                >
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
    @endif

            @push('scripts')
            <!-- QZ Tray integration: adjust URL to your qz-tray.js if needed -->
            <script src="http://localhost:8181/qz-tray.js"></script>
            <script>
                // Lazy connect helper
                async function ensureQzConnected() {
                    if (!window.qz) return false;
                    if (qz.websocket.isActive()) return true;
                    try {
                        await qz.websocket.connect();
                        console.info('qz connected');
                        return true;
                    } catch (e) {
                        console.warn('qz connect failed', e);
                        return false;
                    }
                }

                window.addEventListener('order-processed', async function (e) {
                    const orderId = e.detail.orderId;
                    if (!orderId) return;

                    // fetch printable invoice text from server
                    try {
                        const res = await fetch(`/print/invoice/${orderId}`);
                        if (!res.ok) throw new Error('Failed to fetch invoice');
                        const content = await res.text();

                        const connected = await ensureQzConnected();
                        if (!connected) {
                            // fallback: open in new window for manual print
                            const w = window.open('', '_blank');
                            w.document.write('<pre>' + content.replace(/</g,'&lt;') + '</pre>');
                            w.document.close();
                            return;
                        }

                        // choose printer (you can hardcode name or use qz.printers.find())
                        const printer = await qz.printers.find();

                        const config = qz.configs.create(printer);

                        // send raw text data to printer
                        const data = [{ type: 'raw', format: 'plain', data: content }];

                        await qz.print(config, data);
                        console.info('Printed via QZ Tray for order', orderId);
                    } catch (err) {
                        console.error('Auto-print failed', err);
                    }
                });
            </script>
            @endpush
</div>