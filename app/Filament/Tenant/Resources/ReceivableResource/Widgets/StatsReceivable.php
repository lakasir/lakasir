<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\Widgets;

use App\Models\Tenants\Member;
use App\Models\Tenants\Receivable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class StatsReceivable extends BaseWidget
{
    protected function getStats(): array
    {
        $receivable = Receivable::query()
            ->select(
                DB::raw('SUM(total_receivable) as total_receivable'),
                DB::raw('SUM(rest_receivable) as rest_receivable')
            )
            ->first();
        $member = Member::query()
            ->whereHas('receivables')
            ->count();

        return [
            Stat::make(__('Total receivable'), Number::abbreviate($receivable->total_receivable ?? 0)),
            Stat::make(__('Total rest receivable'), Number::abbreviate($receivable->rest_receivable ?? 0)),
            Stat::make(__('Member'), $member),
        ];
    }
}
