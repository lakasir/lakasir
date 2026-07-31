<?php

namespace App\Livewire\PaymentMethods;

use App\Models\Tenants\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Metode Pembayaran - Lakasir POS', 'header' => 'Metode Pembayaran'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $paymentMethodId = null;
    
    // Fields
    public $name = '';
    public $is_cash = false;
    public $is_debit = false;
    public $is_credit = false;
    public $is_wallet = false;

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
        $pm = PaymentMethod::findOrFail($id);
        $this->paymentMethodId = $pm->id;
        
        $this->name = $pm->name;
        $this->is_cash = $pm->is_cash;
        $this->is_debit = $pm->is_debit;
        $this->is_credit = $pm->is_credit;
        $this->is_wallet = $pm->is_wallet;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'is_cash' => 'boolean',
            'is_debit' => 'boolean',
            'is_credit' => 'boolean',
            'is_wallet' => 'boolean',
        ]);

        $data = [
            'name' => $this->name,
            'is_cash' => $this->is_cash ? 1 : 0,
            'is_debit' => $this->is_debit ? 1 : 0,
            'is_credit' => $this->is_credit ? 1 : 0,
            'is_wallet' => $this->is_wallet ? 1 : 0,
        ];

        if ($this->isEdit) {
            $pm = PaymentMethod::findOrFail($this->paymentMethodId);
            $pm->update($data);
        } else {
            PaymentMethod::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $pm = PaymentMethod::findOrFail($id);
        $pm->delete();
    }

    public function resetForm()
    {
        $this->paymentMethodId = null;
        $this->name = '';
        $this->is_cash = false;
        $this->is_debit = false;
        $this->is_credit = false;
        $this->is_wallet = false;
        $this->resetValidation();
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.payment-methods.index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
