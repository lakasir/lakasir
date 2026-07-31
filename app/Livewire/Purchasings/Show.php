<?php

namespace App\Livewire\Purchasings;

use App\Constants\PurchasingStatus;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Stock;
use App\Services\Tenants\PurchasingService;
use App\Services\Tenants\StockService;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Purchasing $purchasing;
    
    // Form Item (Stock) attributes
    public $product_id;
    public $stock_qty; // maps to 'stock'
    public $expired;
    public $initial_price;
    public $selling_price;
    
    public $searchProduct = '';
    public $products = [];

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'stock_qty' => 'required|numeric|min:1',
        'initial_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|gte:initial_price',
        'expired' => 'nullable|date',
    ];

    public function mount(Purchasing $purchasing)
    {
        $this->purchasing = $purchasing->load(['supplier', 'paymentMethod', 'stocks.product']);
    }

    public function updatedSearchProduct()
    {
        if (strlen($this->searchProduct) > 2) {
            $this->products = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('barcode', 'like', '%' . $this->searchProduct . '%')
                ->take(10)->get();
        } else {
            $this->products = [];
        }
    }

    public function selectProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->product_id = $product->id;
            $this->searchProduct = $product->name;
            $this->initial_price = $product->initial_price;
            $this->selling_price = $product->selling_price;
            $this->stock_qty = 1;
            $this->products = [];
        }
    }

    public function addItem()
    {
        if ($this->purchasing->status == PurchasingStatus::approved) {
            session()->flash('error', 'Tidak dapat menambah item pada pesanan yang sudah disetujui.');
            return;
        }

        $this->validate();

        $stockService = new StockService();
        $purchasingService = new PurchasingService();

        try {
            $data = [
                'product_id' => $this->product_id,
                'stock' => $this->stock_qty,
                'initial_price' => $this->initial_price,
                'selling_price' => $this->selling_price,
                'expired' => $this->expired ?: null,
            ];

            $stockService->create($data, $this->purchasing);
            
            // Update purchasing totals
            $purchasingService->update(
                $this->purchasing->id,
                $purchasingService->getUpdatedPrice($this->purchasing)
            );

            $this->purchasing->refresh();
            $this->resetItemForm();
            $this->dispatch('close-modal', 'add-item-modal');
            session()->flash('message', 'Item berhasil ditambahkan.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menambah item: ' . $e->getMessage());
        }
    }

    public function removeItem($stockId)
    {
        if ($this->purchasing->status == PurchasingStatus::approved) {
            session()->flash('error', 'Tidak dapat menghapus item pada pesanan yang sudah disetujui.');
            return;
        }

        $stock = Stock::findOrFail($stockId);
        if ($stock->purchasing_id == $this->purchasing->id) {
            $stock->delete();
            
            $purchasingService = new PurchasingService();
            $purchasingService->update(
                $this->purchasing->id,
                $purchasingService->getUpdatedPrice($this->purchasing)
            );
            
            $this->purchasing->refresh();
            session()->flash('message', 'Item berhasil dihapus.');
        }
    }
    
    public function updatePurchasingStatus($status)
    {
        if ($this->purchasing->stocks->isEmpty()) {
            session()->flash('error', 'Tidak dapat mengubah status karena belum ada item.');
            return;
        }

        if ($status == PurchasingStatus::approved && !can('approve purchasing')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyetujui.');
            return;
        }

        $purchasingService = new PurchasingService();
        $purchasingService->updateStatus($this->purchasing, $status);
        $this->purchasing->refresh();
        session()->flash('message', 'Status pembelian diperbarui.');
    }

    public function resetItemForm()
    {
        $this->product_id = null;
        $this->searchProduct = '';
        $this->stock_qty = null;
        $this->initial_price = null;
        $this->selling_price = null;
        $this->expired = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.purchasings.show')->title('Detail Pembelian ' . $this->purchasing->number);
    }
}
