<div class="space-y-4">
    <!-- Search and Filter -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Products</label>
            <input 
                type="text" 
                wire:model.debounce.300ms="search"
                placeholder="Type to search..." 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
        </div>

        <!-- Category Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
            <select 
                wire:model="categoryId"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2 border border-gray-200 rounded-lg">
        @forelse($products as $product)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer bg-white">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <span class="text-primary-600 font-bold">IDR {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex items-center justify-between mt-3">
                    <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                    <div class="flex items-center space-x-2">
                        @if(isset($selectedProducts[$product->id]))
                            <button 
                                type="button" 
                                wire:click="removeProduct({{ $product->id }})"
                                class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors"
                            >
                                -
                            </button>
                            <span class="font-semibold w-6 text-center">
                                {{ $selectedProducts[$product->id]['quantity'] }}
                            </span>
                        @endif
                        <button 
                            type="button" 
                            wire:click="addProduct({{ $product->id }})"
                            class="bg-primary-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors"
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                No products found
            </div>
        @endforelse
    </div>

    <!-- Selected Items Summary -->
    @if(count($selectedProducts) > 0)
        <div class="border-t pt-4 mt-4">
            <h4 class="font-semibold mb-2 text-gray-700">Selected Items:</h4>
            <div class="space-y-2 max-h-32 overflow-y-auto">
                @foreach($selectedProducts as $item)
                    <div class="flex justify-between items-center text-sm bg-gray-50 px-3 py-2 rounded">
                        <span class="font-medium">{{ $item['product_name'] }}</span>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <button 
                                    type="button" 
                                    wire:click="removeProduct({{ $item['product_id'] }})"
                                    class="bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors text-xs"
                                >
                                    -
                                </button>
                                <span class="font-semibold w-4 text-center">{{ $item['quantity'] }}</span>
                                <button 
                                    type="button" 
                                    wire:click="addProduct({{ $item['product_id'] }})"
                                    class="bg-primary-500 text-white w-5 h-5 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors text-xs"
                                >
                                    +
                                </button>
                            </div>
                            <span class="text-primary-600 font-semibold">
                                IDR {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>