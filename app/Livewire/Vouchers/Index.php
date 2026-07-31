<?php

namespace App\Livewire\Vouchers;

use App\Models\Tenants\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Diskon & Voucher - Lakasir POS', 'header' => 'Daftar Voucher'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $voucherId = null;
    
    // Voucher fields
    public $name = '';
    public $code = '';
    public $type = 'percentage';
    public $nominal = 0;
    public $kuota = 0;
    public $start_date = '';
    public $expired = '';
    public $minimal_buying = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->start_date = now()->format('Y-m-d');
        $this->expired = now()->addDays(7)->format('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $voucher = Voucher::findOrFail($id);
        $this->voucherId = $voucher->id;
        
        $this->name = $voucher->name;
        $this->code = $voucher->code;
        $this->type = $voucher->type;
        $this->nominal = $voucher->nominal;
        $this->kuota = $voucher->kuota;
        // Make sure to format date to match input[type="date"]
        $this->start_date = \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d');
        $this->expired = \Carbon\Carbon::parse($voucher->expired)->format('Y-m-d');
        $this->minimal_buying = $voucher->minimal_buying;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:vouchers,code,' . $this->voucherId,
            'type' => 'required|in:percentage,flat',
            'nominal' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->type === 'percentage' && $value > 100) {
                        $fail('Nominal diskon persentase tidak boleh lebih dari 100%.');
                    }
                },
            ],
            'kuota' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'expired' => 'required|date|after_or_equal:start_date',
            'minimal_buying' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama voucher wajib diisi.',
            'code.required' => 'Kode voucher wajib diisi.',
            'code.unique' => 'Kode voucher sudah digunakan.',
            'expired.after_or_equal' => 'Tanggal berakhir tidak boleh lebih kecil dari tanggal mulai.',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'nominal' => $this->nominal,
            'kuota' => $this->kuota,
            'start_date' => $this->start_date,
            'expired' => $this->expired,
            'minimal_buying' => $this->minimal_buying,
        ];

        if ($this->isEdit) {
            $voucher = Voucher::findOrFail($this->voucherId);
            $voucher->update($data);
        } else {
            Voucher::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
    }

    public function resetForm()
    {
        $this->voucherId = null;
        $this->name = '';
        $this->code = '';
        $this->type = 'percentage';
        $this->nominal = 0;
        $this->kuota = 0;
        $this->start_date = '';
        $this->expired = '';
        $this->minimal_buying = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $vouchers = Voucher::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.vouchers.index', [
            'vouchers' => $vouchers,
        ]);
    }
}
