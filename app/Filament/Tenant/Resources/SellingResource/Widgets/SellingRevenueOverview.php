<?php

namespace App\Filament\Tenant\Resources\SellingResource\Widgets;

use App\Models\Tenants\Selling;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SellingRevenueOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $yesterdayRevenue = Selling::query()
            ->select(
                DB::raw('SUM(total_price / 1000) as total_price'),
                DB::raw('SUM(total_cost / 1000) as total_cost'),
                DB::raw('SUM((total_price - total_cost) / 1000) as total_revenue'),
            )
            ->whereBetween('created_at', [
                now()->subDay(1)->startOfDay(),
                now()->subDay(1)->endOfDay(),
            ])
            ->first();
        $todayRevenue = Selling::query()
            ->select(
                DB::raw('SUM(total_price / 1000) as total_price'),
                DB::raw('SUM(total_cost / 1000) as total_cost'),
                DB::raw('SUM((total_price - total_cost) / 1000) as total_revenue'),
            )
            ->whereBetween('created_at', [
                now()->startOfDay(),
                now()->endOfDay(),
            ])
            ->first();
        $readable = match (true) {
            $todayRevenue->total_revenue >= 1 => 'K',
            $todayRevenue->total_revenue >= 1000 => 'M',
            $todayRevenue->total_revenue >= 1000000 => 'B',
            default => ''
        };

        // $readableYesterday = match (true) {
        //     $yesterdayRevenue->total_revenue >= 1 => 'K',
        //     $yesterdayRevenue->total_revenue >= 1000 => 'M',
        //     $yesterdayRevenue->total_revenue >= 1000000 => 'B',
        //     default => ''
        // };

        $trend = 'increase';
        $color = 'success';
        $icon = 'heroicon-m-arrow-trending-up';
        if ($yesterdayRevenue->total_revenue > $todayRevenue->total_revenue) {
            $trend = 'decrease';
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
        }
        $prosentase = (($todayRevenue->total_revenue - $yesterdayRevenue->total_revenue) / $yesterdayRevenue->total_revenue) * 100;

        return [
            Stat::make('Today total revenue', $todayRevenue->total_revenue.$readable)
                // ->description(($yesterdayRevenue->total_revenue - $todayRevenue->total_revenue).$readableYesterday.' '.$trend)
                ->descriptionIcon($icon)
                ->description($prosentase.'% '.$trend)
                ->chart([$yesterdayRevenue->total_revenue, $todayRevenue->total_revenue])
                // ->descriptionColor()
                ->color($color),
        ];
    }
}
