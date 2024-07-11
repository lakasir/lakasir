<?php

namespace App\Filament\Tenant\Resources\DebtResource\Widgets;

use App\Models\Tenants\Debt;
use App\Models\Tenants\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class StatsDebt extends BaseWidget
{
    protected function getStats(): array
    {
        $debt = Debt::query()
            ->select(
                DB::raw('SUM(total_debt) as total_debt'),
                DB::raw('SUM(rest_debt) as rest_debt')
            )
            ->first();
        $member = Member::query()
            ->whereHas('debts')
            ->count();

        return [
            Stat::make(__('Total debt'), Number::abbreviate($debt->total_debt ?? 0)),
            Stat::make(__('Total rest debt'), Number::abbreviate($debt->rest_debt ?? 0)),
            Stat::make(__('Member'), $member),
        ];
    }
}
