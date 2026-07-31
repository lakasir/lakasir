<?php

namespace App\Livewire\StockOpnames;

use App\Constants\StockOpnameStatus;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Form attributes
    public $isEdit = false;
    public $formId = null;
    
    public $pic;
    public $date;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->pic = auth()->user()->name;
        $this->date = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->isEdit = false;
        $this->formId = null;
        $this->pic = auth()->user()->name;
        $this->date = now()->format('Y-m-d');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'stockopname-modal');
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->formId = $id;

        $stockOpname = StockOpname::findOrFail($id);
        $this->pic = $stockOpname->pic;
        $this->date = $stockOpname->date;

        $this->dispatch('open-modal', 'stockopname-modal');
    }

    public function save()
    {
        $this->validate([
            'pic' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $stockOpnameService = new StockOpnameService();

        $data = [
            'pic' => $this->pic,
            'date' => $this->date,
        ];

        try {
            if ($this->isEdit) {
                $stockOpname = StockOpname::findOrFail($this->formId);
                $stockOpnameService->update($stockOpname, $data);
                $this->dispatch('close-modal', 'stockopname-modal');
                session()->flash('message', 'Stock Opname berhasil diperbarui.');
            } else {
                $data['user_id'] = auth()->id();
                $data['number'] = $stockOpnameService->generateNumber('SO');
                $stockOpname = $stockOpnameService->create($data);
                
                $this->dispatch('close-modal', 'stockopname-modal');
                session()->flash('message', 'Stock Opname berhasil dibuat.');
                
                return redirect()->route('stock-opnames.show', $stockOpname->id);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $stockOpname = StockOpname::findOrFail($id);
        
        if ($stockOpname->status == StockOpnameStatus::approved) {
            session()->flash('error', 'Tidak dapat menghapus Stock Opname yang sudah disetujui.');
            return;
        }
        
        $stockOpnameService = new StockOpnameService();
        $stockOpnameService->delete($stockOpname);
        
        session()->flash('message', 'Stock Opname berhasil dihapus.');
    }

    public function updateStatus($id, $status)
    {
        $stockOpname = StockOpname::findOrFail($id);
        
        if ($stockOpname->stockOpnameItems->isEmpty() && $status == StockOpnameStatus::approved) {
            session()->flash('error', 'Tidak dapat menyetujui karena belum ada item.');
            return;
        }

        if ($status == StockOpnameStatus::approved && !can('approve stock opname')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyetujui.');
            return;
        }

        $stockOpnameService = new StockOpnameService();
        $stockOpnameService->updateStatus($stockOpname, $status);
        session()->flash('message', 'Status diperbarui.');
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = StockOpname::query()
            ->withCount('stockOpnameItems')
            ->when($this->search, function ($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                  ->orWhere('pic', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderByDesc('created_at');

        return view('livewire.stock-opnames.index', [
            'stockOpnames' => $query->paginate($this->perPage),
            'statuses' => StockOpnameStatus::all(),
        ])->title('Penyesuaian Stok (Stock Opname)');
    }
}
