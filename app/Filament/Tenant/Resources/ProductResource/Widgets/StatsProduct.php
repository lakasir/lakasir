<?php

namespace App\Filament\Tenant\Resources\ProductResource\Widgets;

use App\Models\Tenants\SellingDetail;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class StatsProduct extends BaseWidget
{
    public $recordId;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $selingDetail = SellingDetail::query()
            ->addSelect(
                DB::raw('sum(cost) as cost'),
                DB::raw('sum(qty) as sold'),
                DB::raw('sum(price - discount_price) as price'),
                DB::raw('sum(discount_price) as discount_price')
            )
            ->whereProductId($this->recordId)->groupBy('product_id')
            ->first();

        return [
            Stat::make(__('Sold'), $selingDetail->sold ?? 0),
            Stat::make(__('Revenue'), Number::abbreviate(
                ($selingDetail->price ?? 0) - ($selingDetail->cost ?? 0),
            )),
            Stat::make(__('Discount'), Number::abbreviate(
                $selingDetail->discount_price ?? 0
            )),
        ];
    }
}
