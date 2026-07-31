<?php

namespace App\Livewire\Purchasings;

use App\Constants\PurchasingStatus;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Supplier;
use App\Services\Tenants\PurchasingService;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Form attributes
    public $isEdit = false;
    public $formId = null;
    
    public $supplier_id;
    public $payment_method_id;
    public $date;
    public $due_date;
    public $image;

    public $suppliers = [];
    public $paymentMethods = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->suppliers = Supplier::orderBy('name')->get();
        $this->paymentMethods = PaymentMethod::orderBy('name')->get();
        $this->date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->isEdit = false;
        $this->formId = null;
        $this->supplier_id = null;
        $this->payment_method_id = null;
        $this->date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
        $this->image = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'purchasing-modal');
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->formId = $id;

        $purchasing = Purchasing::findOrFail($id);
        $this->supplier_id = $purchasing->supplier_id;
        $this->payment_method_id = $purchasing->payment_method_id;
        $this->date = $purchasing->date;
        $this->due_date = $purchasing->due_date;
        // Not loading image for edit in simple form, user can re-upload if needed

        $this->dispatch('open-modal', 'purchasing-modal');
    }

    public function save()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'image' => 'nullable|image|max:2048',
        ]);

        $purchasingService = new PurchasingService();

        $data = [
            'supplier_id' => $this->supplier_id,
            'payment_method_id' => $this->payment_method_id,
            'date' => $this->date,
            'due_date' => $this->due_date,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('purchasings', 'public');
        }

        try {
            if ($this->isEdit) {
                $purchasingService->update($this->formId, $data);
                $this->dispatch('close-modal', 'purchasing-modal');
                session()->flash('message', 'Pembelian berhasil diperbarui.');
            } else {
                $data['user_id'] = auth()->id();
                $data['number'] = $purchasingService->generateNumber('PO');
                $purchasing = $purchasingService->create($data);
                
                $this->dispatch('close-modal', 'purchasing-modal');
                session()->flash('message', 'Pembelian berhasil dibuat.');
                
                return redirect()->route('purchasings.show', $purchasing->id);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function togglePaymentStatus($id)
    {
        $purchasing = Purchasing::findOrFail($id);
        $purchasing->payment_status = !$purchasing->payment_status;
        $purchasing->save();
        session()->flash('message', 'Status pembayaran diperbarui.');
    }

    public function updatePurchasingStatus($id, $status)
    {
        $purchasing = Purchasing::findOrFail($id);
        if ($purchasing->stocks->isEmpty()) {
            session()->flash('error', 'Tidak dapat mengubah status karena belum ada item (Stocks).');
            return;
        }

        if ($status == PurchasingStatus::approved && !can('approve purchasing')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyetujui.');
            return;
        }

        $purchasingService = new PurchasingService();
        $purchasingService->updateStatus($purchasing, $status);
        session()->flash('message', 'Status pembelian diperbarui.');
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = Purchasing::query()
            ->with(['supplier', 'paymentMethod'])
            ->withCount('stocks')
            ->when($this->search, function ($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('supplier', function ($sq) {
                      $sq->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderByDesc('created_at');

        return view('livewire.purchasings.index', [
            'purchasings' => $query->paginate($this->perPage),
            'statuses' => PurchasingStatus::all(),
        ])->title('Pembelian');
    }
}
