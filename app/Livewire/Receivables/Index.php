<?php

namespace App\Livewire\Receivables;

use App\Models\Tenants\Receivable;
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

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = Receivable::query()
            ->with(['member', 'selling'])
            ->when($this->search, function ($q) {
                $q->whereHas('member', function ($qMember) {
                    $qMember->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('selling', function ($qSelling) {
                    $qSelling->where('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderByDesc('created_at');

        return view('livewire.receivables.index', [
            'receivables' => $query->paginate($this->perPage),
        ])->title('Piutang');
    }
}
