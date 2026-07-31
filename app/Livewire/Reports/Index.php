<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app', ['title' => 'Laporan - Lakasir POS', 'header' => 'Laporan'])]
class Index extends Component
{
    public $activeTab = 'selling'; // selling, purchasing, product, cashier
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    #[Computed]
    public function reportData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay()->setTimezone('UTC');
        $end = Carbon::parse($this->endDate)->endOfDay()->setTimezone('UTC');

        if ($this->activeTab === 'selling') {
            return Selling::with('cashier')
                ->whereBetween('created_at', [$start, $end])
                ->isPaid()
                ->latest()
                ->get();
        }

        if ($this->activeTab === 'purchasing') {
            return Purchasing::with('supplier')
                ->whereBetween('created_at', [$start, $end])
                ->latest()
                ->get();
        }

        if ($this->activeTab === 'product') {
            return Product::with('category')->get();
        }

        if ($this->activeTab === 'cashier') {
            return Selling::with('cashier')
                ->whereBetween('created_at', [$start, $end])
                ->isPaid()
                ->select('cashier_id', DB::raw('count(*) as total_transactions'), DB::raw('sum(total_price) as total_revenue'))
                ->groupBy('cashier_id')
                ->get();
        }

        return [];
    }

    public function render()
    {
        return view('livewire.reports.index');
    }
}
