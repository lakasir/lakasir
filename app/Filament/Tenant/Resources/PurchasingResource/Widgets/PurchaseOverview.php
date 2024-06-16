<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Widgets;

use App\Constants\PurchasingStatus;
use App\Models\Tenants\Purchasing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PurchaseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $approved = Purchasing::whereStatus(PurchasingStatus::approved)->count();
        $reviewing = Purchasing::whereStatus(PurchasingStatus::reviewing)->count();
        $pending = Purchasing::whereStatus(PurchasingStatus::pending)->count();

        return [
            Stat::make(__('Approved'), $approved),
            Stat::make(__('Reviewing'), $reviewing),
            Stat::make(__('Pending'), $pending),
        ];
    }
}
