<?php

namespace App\Livewire\Sellings;

use App\Models\Tenants\Selling;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $paymentStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingPaymentStatus()
    {
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = Selling::query()
            ->with(['member', 'paymentMethod', 'cashDrawer', 'user'])
            ->when($this->search, function ($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('member', function ($mq) {
                      $mq->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->paymentStatus !== '', function ($q) {
                $q->where('is_paid', $this->paymentStatus);
            })
            ->orderByDesc('created_at');

        return view('livewire.sellings.index', [
            'sellings' => $query->paginate($this->perPage),
        ])->title('Riwayat Transaksi');
    }
}
