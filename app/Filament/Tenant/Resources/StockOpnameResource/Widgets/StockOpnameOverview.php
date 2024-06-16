<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Widgets;

use App\Constants\StockOpnameStatus;
use App\Models\Tenants\StockOpname;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockOpnameOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $approved = StockOpname::whereStatus(StockOpnameStatus::approved)->count();
        $reviewing = StockOpname::whereStatus(StockOpnameStatus::reviewing)->count();
        $pending = StockOpname::whereStatus(StockOpnameStatus::pending)->count();

        return [
            Stat::make(__('Approved'), $approved),
            Stat::make(__('Reviewing'), $reviewing),
            Stat::make(__('Pending'), $pending),
        ];
    }
}
