<?php

namespace App\Livewire\Pos;

use App\Models\Tenants\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Kasir - Lakasir POS', 'header' => 'Kasir'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $cart = [];
    public $subtotal = 0;
    public $tax = 0;
    public $discount = 0;
    public $total = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        // Check if exists
        $existing = collect($this->cart)->firstWhere('product_id', $productId);
        
        if ($existing) {
            $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
                if ($item['product_id'] == $productId) {
                    $item['qty'] += 1;
                    $item['subtotal'] = $item['qty'] * $item['price'];
                }
                return $item;
            })->toArray();
        } else {
            $this->cart[] = [
                'id' => uniqid(),
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price ?? 0,
                'qty' => 1,
                'subtotal' => $product->selling_price ?? 0,
            ];
        }

        $this->calculateTotals();
    }

    public function removeFromCart($cartId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($cartId) {
            return $item['id'] === $cartId;
        })->values()->toArray();

        $this->calculateTotals();
    }

    public function updateQty($cartId, $action)
    {
        $this->cart = collect($this->cart)->map(function ($item) use ($cartId, $action) {
            if ($item['id'] === $cartId) {
                if ($action === 'increment') {
                    $item['qty'] += 1;
                } elseif ($action === 'decrement' && $item['qty'] > 1) {
                    $item['qty'] -= 1;
                }
                $item['subtotal'] = $item['qty'] * $item['price'];
            }
            return $item;
        })->toArray();

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('subtotal');
        // Example tax 11% if needed, keeping 0 for now
        $this->tax = 0; 
        $this->total = $this->subtotal + $this->tax - $this->discount;
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('livewire.pos.index', [
            'products' => $products,
        ]);
    }
}
