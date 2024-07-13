<?php

namespace App\Filament\Tenant\Resources\ProductResource\Widgets;

use App\Models\Tenants\Product;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\Setting;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class StatsProduct extends BaseWidget
{
    public Product $product;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $product = $this->product;
        $selingDetail = SellingDetail::query()
            ->addSelect(
                DB::raw('sum(cost) as cost'),
                DB::raw('sum(qty) as sold'),
                DB::raw('sum(price - discount_price) as price'),
                DB::raw('sum(discount_price) as discount_price')
            )
            ->whereProductId($product->id)->groupBy('product_id')
            ->first();

        $stock = null;
        if (! $product->is_non_stock) {
            $minStock = Setting::get('minimum_stock_nofication', 0);
            $description = null;
            if ($product->stock <= $minStock) {
                $productName = [
                    'product' => $product->name,
                ];
                $description = $product->stock > 0
                    ? __('notifications.stocks.single-runs-out', $productName)
                    : __('notifications.stocks.single-out-of-stock', $productName);
            }
            $stock = Stat::make(__('Stock'), $product->stock)
                ->description($description)
                ->color(Color::Yellow);
        }

        return [
            $stock,
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
