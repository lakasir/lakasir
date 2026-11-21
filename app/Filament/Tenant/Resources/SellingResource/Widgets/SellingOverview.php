<?php

namespace App\Filament\Tenant\Resources\SellingResource\Widgets;

use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Services\Tenants\DashboardService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class SellingOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalRevenue = $this->getTotalRevenue();
        $todaySales = $this->getSalesToday();
        $discountToday = $this->getDiscountToday();

        return [
            can('read revenue overview') ? Stat::make(__('Today total revenue'), $totalRevenue['total_revenue'])
                ->descriptionIcon($totalRevenue['icon'])
                ->description($totalRevenue['description'])
                ->chart([$totalRevenue['yesterdayRevenue'], $totalRevenue['todayRevenue']])
                ->color($totalRevenue['color']) : null,
            can('read sales overview') ? Stat::make(__('Sales today'), $todaySales) : null,
            can('read sales overview') ? Stat::make(__('Discount today'), $discountToday) : null,
        ];
    }

    private function getDiscountToday()
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

        return Number::abbreviate($totalDiscount);
    }

    private function getSalesToday()
    {
        $carbon = now(Profile::get()->timezone);
        $today = $carbon->startOfDay()->format('Y-m-d H:i:s e');
        $startDate = Carbon::parse($today)->setTimezone(Profile::get()->timezone ?? 'UTC');

        $salesToday = Selling::whereDate('date', $startDate)->count();

        return $salesToday;
    }

    private function getTotalRevenue()
    {
        $dashboard = app(DashboardService::class);
        $timezone = Profile::get()->timezone ?? config('app.timezone');
        $carbon = now($timezone);
        $startOfDay = $carbon->startOfDay();
        $startOfYesterday = $startOfDay->copy()->subDay();

        $totalYesterdayRevenue = $dashboard->revenue($startOfYesterday, $startOfDay->copy());
        $totalTodayRevenue = $dashboard->revenue($startOfDay, $startOfDay->copy()->addDay());

        $readable = $this->getReadableSuffix($totalTodayRevenue);

        $trendData = $this->getTrendData($totalYesterdayRevenue, $totalTodayRevenue);

        $percentage = $totalYesterdayRevenue ? (($totalTodayRevenue - $totalYesterdayRevenue) / $totalYesterdayRevenue) * 100 : 0;

        return [
            'total_revenue' => $readable,
            'description' => round($percentage).'% '.$trendData['trend'],
            'yesterdayRevenue' => $totalYesterdayRevenue,
            'todayRevenue' => $totalTodayRevenue,
            'color' => $trendData['color'],
            'icon' => $trendData['icon'],
        ];
    }

    private function getReadableSuffix($totalRevenue)
    {
        return Number::abbreviate($totalRevenue);
    }

    private function getTrendData($totalYesterdayRevenue, $totalTodayRevenue)
    {
        if ($totalYesterdayRevenue > $totalTodayRevenue) {
            return [
                'trend' => __('decrease'),
                'color' => 'danger',
                'icon' => 'heroicon-m-arrow-trending-down',
            ];
        }

        if ($totalYesterdayRevenue < $totalTodayRevenue) {
            return [
                'trend' => __('increase'),
                'color' => 'success',
                'icon' => 'heroicon-m-arrow-trending-up',
            ];
        }

        return [
            'trend' => __('sideway'),
            'color' => 'warning',
            'icon' => 'heroicon-m-minus',
        ];
    }
}
