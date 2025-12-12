{{-- resources/views/livewire/point-of-sale.blade.php --}}
<div class="h-screen bg-gray-100 p-4">
    <div class="grid grid-cols-4 gap-4 h-full">
        
        <!-- LEFT SIDEBAR: PRODUCT CATEGORIES & SEARCH (1/4) -->
        <div class="col-span-1 bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-4">Categories</h2>
            
            <!-- Search Box -->
            <div class="mb-4">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search products..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Category List -->
            <div class="space-y-2 max-h-96 overflow-y-auto">
                <button 
                    wire:click="$set('selectedCategoryId', null)"
                    class="w-full text-left px-3 py-2 rounded-lg {{ !$selectedCategoryId ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
                >
                    📦 All Products
                </button>
                
                @foreach($categories as $category)
                <button 
                    wire:click="$set('selectedCategoryId', {{ $category->id }})"
                    class="w-full text-left px-3 py-2 rounded-lg {{ $selectedCategoryId == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
                >
                    {{ $category->name }} 
                    <span class="text-xs opacity-75">({{ $category->products_count }})</span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- MAIN CONTENT: PRODUCT GRID (2/4) -->
        <div class="col-span-2 bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-4">
                @if($selectedCategoryId)
                    {{ $categories->firstWhere('id', $selectedCategoryId)->name ?? 'Products' }}
                @else
                    All Products
                @endif
                ({{ count($products) }})
            </h2>
            
            <!-- Product Grid -->
            <div class="grid grid-cols-3 gap-3 max-h-[calc(100vh-200px)] overflow-y-auto">
                @foreach($products as $product)
                <button 
                    wire:click="addToCart({{ $product->id }})"
                    class="p-3 border border-gray-200 rounded-lg text-left hover:bg-blue-50 hover:border-blue-300 transition-all duration-200"
                    wire:key="product-{{ $product->id }}"
                >
                    <div class="font-semibold text-sm text-gray-800">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500 mt-1">Stock: {{ $product->stock }}</div>
                    <div class="font-bold text-green-600 mt-2 text-sm">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </button>
                @endforeach
                
                @if($products->isEmpty())
                <div class="col-span-3 text-center py-8 text-gray-500">
                    No products found
                </div>
                @endif
            </div>
        </div>

        <!-- RIGHT SIDEBAR: CART & PAYMENT (1/4) -->
        <div class="col-span-1 bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Order Summary</h2>
                <button 
                    wire:click="clearCart"
                    class="text-red-500 text-sm hover:text-red-700"
                    {{ empty($cart) ? 'disabled' : '' }}
                >
                    Clear
                </button>
            </div>

            <!-- Cart Items -->
            <div class="space-y-2 max-h-40 overflow-y-auto mb-4">
                @forelse($cart as $index => $item)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                    <div class="flex-1">
                        <div class="text-sm font-medium">{{ $item['product_name'] }}</div>
                        <div class="flex items-center space-x-2 mt-1">
                            <button 
                                wire:click="updateQuantity({{ $index }}, {{ $item['qty'] - 1 }})"
                                class="w-6 h-6 bg-gray-200 rounded text-xs flex items-center justify-center"
                                {{ $item['qty'] <= 1 ? 'disabled' : '' }}
                            >-</button>
                            <span class="text-xs">{{ $item['qty'] }}</span>
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
</div>