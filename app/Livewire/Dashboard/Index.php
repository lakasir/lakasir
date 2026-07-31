<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

#[Layout('layouts.app', ['title' => 'Dashboard - Lakasir POS', 'header' => 'Dashboard'])]
class Index extends Component
{
    public function getDiscountToday()
    {
        $carbon = now(Profile::get()->timezone);
        $today = $carbon->startOfDay()->format('Y-m-d H:i:s e');
        $startDate = Carbon::parse($today)->setTimezone('UTC');
        $endDate = Carbon::parse($today)->setTimezone('UTC')->addDay();
        
        $totalDiscountSellings = Selling::whereBetween('date', [$startDate, $endDate])
            ->sum('discount_price');

        $totalDiscountSellingDetails = SellingDetail::whereHas('selling', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->sum('discount_price');

        $totalDiscount = $totalDiscountSellings + $totalDiscountSellingDetails;

        return $totalDiscount;
    }

    public function getSalesToday()
    {
        $carbon = now(Profile::get()->timezone);
        $today = $carbon->startOfDay()->format('Y-m-d H:i:s e');
        $startDate = Carbon::parse($today)->setTimezone(Profile::get()->timezone ?? 'UTC');

        return Selling::whereDate('date', $startDate)->count();
    }

    public function getTotalRevenue()
    {
        $carbon = now(Profile::get()->timezone);
        $startOfDay = $carbon->startOfDay();
        $startOfYesterday = $startOfDay->copy()->subDay();

        $yesterdayRevenue = $this->calculateRevenue($startOfYesterday, $startOfDay);
        $todayRevenue = $this->calculateRevenue($startOfDay, $startOfDay->copy()->addDay());

        $totalYesterdayRevenue = $this->calculateTotalRevenue($yesterdayRevenue);
        $totalTodayRevenue = $this->calculateTotalRevenue($todayRevenue);

        $percentage = $totalYesterdayRevenue ? (($totalTodayRevenue - $totalYesterdayRevenue) / $totalYesterdayRevenue) * 100 : 0;
        $percentage = round($percentage);

        $trend = 'sideway';
        if ($totalYesterdayRevenue > $totalTodayRevenue) $trend = 'decrease';
        if ($totalYesterdayRevenue < $totalTodayRevenue) $trend = 'increase';

        return [
            'total' => $totalTodayRevenue,
            'yesterday' => $totalYesterdayRevenue,
            'percentage' => $percentage,
            'trend' => $trend,
        ];
    }

    private function calculateRevenue($start, $end)
    {
        return Selling::query()
            ->select(
                DB::raw('SUM(sellings.discount_price) as discount_price'),
                DB::raw('SUM(sellings.total_discount_per_item) as total_discount_per_item'),
                DB::raw('SUM(sellings.tax_price) as tax_price'),
                DB::raw('SUM(sellings.total_price) as total_price'),
                DB::raw('SUM(sellings.total_cost) as total_cost'),
            )
            ->isPaid()
            ->whereBetween('sellings.created_at', [
                $start->setTimezone('UTC'),
                $end->setTimezone('UTC'),
            ])
            ->first();
    }

    private function calculateTotalRevenue($revenue)
    {
        if (!$revenue) return 0;
        $grossProfit = $revenue->total_price - $revenue->tax_price - $revenue->total_discount_per_item - $revenue->discount_price;
        return $grossProfit - $revenue->total_cost;
    }

    public function getRecentTransactions()
    {
        return Selling::with('cashier')
            ->isPaid()
            ->latest('date')
            ->take(5)
            ->get();
    }

    public function getSalesChartData()
    {
        $timezone = Profile::get()->timezone ?? 'UTC';
        $carbon = now($timezone);
        $endDate = $carbon->endOfDay();
        $startDate = $carbon->copy()->subDays(6)->startOfDay();

        $categories = [];
        $series = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $categories[] = $current->format('d M');
            $series[$current->format('Y-m-d')] = 0;
            $current->addDay();
        }

        $sellings = Selling::isPaid()
            ->select('total_price', 'created_at')
            ->whereBetween('created_at', [
                $startDate->copy()->setTimezone('UTC'), 
                $endDate->copy()->setTimezone('UTC')
            ])->get();

        foreach ($sellings as $selling) {
            $localDate = Carbon::parse($selling->created_at)->setTimezone($timezone)->format('Y-m-d');
            if (isset($series[$localDate])) {
                $series[$localDate] += $selling->total_price;
            }
        }

        return [
            'categories' => $categories,
            'series' => array_values($series)
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.index', [
            'totalRevenue' => $this->getTotalRevenue(),
            'salesToday' => $this->getSalesToday(),
            'discountToday' => $this->getDiscountToday(),
            'recentTransactions' => $this->getRecentTransactions(),
            'salesChartData' => $this->getSalesChartData(),
        ]);
    }
}
