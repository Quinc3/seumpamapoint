<?php
// app/Livewire/PointOfSale.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;

class PointOfSale extends Component
{
    public $cart = [];
    public $total = 0;
    public $tax = 0;
    public $grandTotal = 0;
    public $categories = [];
    public $selectedCategoryId = null;
    public $search = '';
    
    // Order details
    public $customerName = '';
    public $orderType = 'dine_in';
    public $tableNumber = '';
    public $paymentMethod = 'cash';
    public $discount = 0;
    public $cashReceived = 0;
    public $cashChange = 0;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::with(['products' => function($query) {
            $query->where('is_active', true)
                  ->where('stock', '>', 0)
                  ->orderBy('name');
        }])->where('is_active', true)->get();
        
        // Load products count for each category
        foreach ($this->categories as $category) {
            $category->products_count = $category->products->count();
        }
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Product out of stock!');
            return;
        }

        // Cek jika product sudah ada di cart
        $existingIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            // Update quantity jika sudah ada
            $this->cart[$existingIndex]['qty']++;
            $this->cart[$existingIndex]['subtotal'] = 
                $this->cart[$existingIndex]['price'] * $this->cart[$existingIndex]['qty'];
        } else {
            // Add new item
            $this->cart[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'subtotal' => $product->price,
            ];
        }

        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        if (isset($this->cart[$index])) {
            array_splice($this->cart, $index, 1);
            $this->calculateTotals();
        }
    }

    public function updateQuantity($index, $newQty)
    {
        if (isset($this->cart[$index]) && $newQty > 0) {
            $this->cart[$index]['qty'] = $newQty;
            $this->cart[$index]['subtotal'] = 
                $this->cart[$index]['price'] * $newQty;
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->total = collect($this->cart)->sum('subtotal');
        $discountAmount = $this->total * ($this->discount / 100);
        $this->grandTotal = max(0, $this->total - $discountAmount);
        
        // Calculate change jika cash
        if ($this->paymentMethod === 'cash') {
            $this->cashChange = max(0, $this->cashReceived - $this->grandTotal);
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->calculateTotals();
    }

    public function processOrder()
    {
        // Validasi
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        if ($this->paymentMethod === 'cash' && $this->cashReceived < $this->grandTotal) {
            session()->flash('error', 'Cash received is less than total amount!');
            return;
        }

        try {
            // Create order
            $order = Order::create([
                'customer_name' => $this->customerName ?: 'Walk-in Customer',
                'order_type' => $this->orderType,
                'table_number' => $this->orderType === 'dine_in' ? $this->tableNumber : null,
                'total_price' => $this->total,
                'discount_amount' => $this->total * ($this->discount / 100),
                'total_payment' => $this->grandTotal,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
                'status' => 'completed',
                'cash_received' => $this->cashReceived,
                'cash_change' => $this->cashChange,
            ]);

            // Create order details
            foreach ($this->cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['qty']);
                
                if ($product->stock <= 0) {
                    $product->update(['in_stock' => false]);
                }
            }

            // Reset form
            $this->clearCart();
            $this->customerName = '';
            $this->tableNumber = '';
            $this->cashReceived = 0;
            $this->discount = 0;

            session()->flash('success', 'Order processed successfully! Order #' . $order->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Error processing order: ' . $e->getMessage());
        }
    }

    public function getFilteredProducts()
    {
        $query = Product::where('is_active', true)
                       ->where('stock', '>', 0);

        // Filter by category
        if ($this->selectedCategoryId) {
            $query->where('category_id', $this->selectedCategoryId);
        }

        // Filter by search
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.point-of-sale', [
            'products' => $this->getFilteredProducts()
        ]);
    }
}