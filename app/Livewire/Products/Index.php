<?php

namespace App\Livewire\Products;

use App\Models\Tenants\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Produk - Lakasir POS', 'header' => 'Daftar Produk'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $productId = null;
    
    // Product fields
    public $name = '';
    public $category_id = null;
    public $initial_price = 0;
    public $selling_price = 0;
    public $stock = 0;
    public $sku = '';
    public $barcode = '';
    public $unit = 'PCS';
    public $type = 'product';
    public $is_non_stock = false;
    public $expired = null;

    public function updatedType($value)
    {
        if ($value === 'service') {
            $this->stock = 0;
        }
    }

    public function updatedIsNonStock($value)
    {
        if ($value) {
            $this->stock = 0;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->initial_price = $product->initial_price;
        $this->selling_price = $product->selling_price;
        $this->stock = $product->stock;
        $this->sku = $product->sku;
        $this->barcode = $product->barcode;
        $this->unit = $product->unit;
        $this->type = $product->type;
        $this->is_non_stock = $product->is_non_stock;
        $this->expired = $product->expired;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric|min:0',
            'initial_price' => 'required|numeric|min:0|lte:selling_price',
            'stock' => 'required|numeric|min:0',
            'type' => 'required|in:product,service',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'initial_price.lte' => 'Harga beli tidak boleh lebih besar dari harga jual.',
        ]);

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'initial_price' => $this->initial_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'unit' => $this->unit ?? 'PCS',
            'type' => $this->type,
            'is_non_stock' => $this->is_non_stock ? 1 : 0,
            'expired' => $this->expired,
        ];

        if ($this->isEdit) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
        } else {
            Product::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->category_id = null;
        $this->initial_price = 0;
        $this->selling_price = 0;
        $this->stock = 0;
        $this->sku = '';
        $this->barcode = '';
        $this->unit = 'PCS';
        $this->type = 'product';
        $this->is_non_stock = false;
        $this->expired = null;
        $this->resetValidation();
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        $categories = \App\Models\Tenants\Category::orderBy('name')->get();

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
