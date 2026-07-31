<?php

namespace App\Livewire\Sellings;

use App\Models\Tenants\Selling;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Selling $selling;

    public function mount(Selling $selling)
    {
        $this->selling = $selling->load([
            'sellingDetails.product', 
            'member', 
            'paymentMethod', 
            'cashDrawer', 
            'user'
        ]);
    }

    public function markAsPaid()
    {
        $this->selling->update([
            'is_paid' => true,
        ]);
        
        session()->flash('message', 'Transaksi berhasil ditandai sebagai Lunas.');
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.sellings.show')->title('Detail Transaksi ' . $this->selling->number);
    }
}
