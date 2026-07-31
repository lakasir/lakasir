<?php

namespace App\Livewire\StockOpnames;

use App\Constants\StockOpnameStatus;
use App\Models\Tenants\Product;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\StockOpnameItem;
use App\Services\Tenants\StockOpnameService;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public StockOpname $stockOpname;
    
    // Form Item attributes
    public $product_id;
    public $searchProduct = '';
    public $products = [];
    
    public $current_stock;
    public $actual_stock;
    public $missing_stock;
    public $adjustment_type = 'broken';
    public $attachment;

    public $adjustmentTypes = [
        'broken' => 'Rusak',
        'lost' => 'Hilang',
        'expired' => 'Kadaluarsa',
        'manual_input' => 'Input Manual',
        'match' => 'Sesuai',
    ];

    public function mount(StockOpname $stockOpname)
    {
        $this->stockOpname = $stockOpname->load(['stockOpnameItems.product', 'user']);
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
            $this->current_stock = $product->stock;
            $this->actual_stock = $product->stock;
            $this->missing_stock = 0;
            $this->products = [];
        }
    }

    public function updatedActualStock()
    {
        if ($this->actual_stock !== '' && $this->current_stock !== null) {
            $this->missing_stock = $this->current_stock - $this->actual_stock;
            if ($this->missing_stock == 0) {
                $this->adjustment_type = 'match';
            } else if ($this->adjustment_type == 'match') {
                $this->adjustment_type = 'manual_input';
            }
        }
    }

    public function addItem()
    {
        if ($this->stockOpname->status == StockOpnameStatus::approved) {
            session()->flash('error', 'Tidak dapat menambah item pada Stock Opname yang sudah disetujui.');
            return;
        }

        $this->validate([
            'product_id' => 'required|exists:products,id',
            'actual_stock' => 'required|numeric',
            'adjustment_type' => 'required|string',
            'attachment' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'stock_opname_id' => $this->stockOpname->id,
                'product_id' => $this->product_id,
                'current_stock' => $this->current_stock,
                'actual_stock' => $this->actual_stock,
                'missing_stock' => $this->missing_stock,
                'adjustment_type' => $this->adjustment_type,
            ];

            if ($this->attachment) {
                $data['attachment'] = $this->attachment->store('stock-opnames', 'public');
            }

            StockOpnameItem::create($data);
            
            $this->stockOpname->refresh();
            $this->resetItemForm();
            $this->dispatch('close-modal', 'add-item-modal');
            session()->flash('message', 'Item berhasil ditambahkan.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menambah item: ' . $e->getMessage());
        }
    }

    public function removeItem($itemId)
    {
        if ($this->stockOpname->status == StockOpnameStatus::approved) {
            session()->flash('error', 'Tidak dapat menghapus item pada Stock Opname yang sudah disetujui.');
            return;
        }

        $item = StockOpnameItem::findOrFail($itemId);
        if ($item->stock_opname_id == $this->stockOpname->id) {
            $item->delete();
            $this->stockOpname->refresh();
            session()->flash('message', 'Item berhasil dihapus.');
        }
    }
    
    public function updateStatus($status)
    {
        if ($this->stockOpname->stockOpnameItems->isEmpty() && $status == StockOpnameStatus::approved) {
            session()->flash('error', 'Tidak dapat menyetujui karena belum ada item.');
            return;
        }

        if ($status == StockOpnameStatus::approved && !can('approve stock opname')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyetujui.');
            return;
        }

        $stockOpnameService = new StockOpnameService();
        $stockOpnameService->updateStatus($this->stockOpname, $status);
        $this->stockOpname->refresh();
        session()->flash('message', 'Status Stock Opname diperbarui.');
    }

    public function resetItemForm()
    {
        $this->product_id = null;
        $this->searchProduct = '';
        $this->current_stock = null;
        $this->actual_stock = null;
        $this->missing_stock = null;
        $this->adjustment_type = 'broken';
        $this->attachment = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.stock-opnames.show')->title('Detail Stock Opname ' . $this->stockOpname->number);
    }
}
