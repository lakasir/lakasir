<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Selling;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * The dashboard numbers, defined once for the API and the web widget so both
 * show the same thing.
 *
 * - revenue: what the shop keeps from its paid sales — the price after the
 *   line and order discounts and after the tax it has to hand over.
 * - gross profit: revenue minus what the goods cost.
 * - sales: how many paid sales.
 */
class DashboardService
{
    public function revenue(Carbon $start, Carbon $end): float
    {
        $totals = $this->totals($start, $end);

        return (float) $totals->total_price
            - (float) $totals->tax_price
            - (float) $totals->total_discount_per_item
            - (float) $totals->discount_price;
    }

    public function grossProfit(Carbon $start, Carbon $end): float
    {
        $totals = $this->totals($start, $end);

        return $this->revenue($start, $end) - (float) $totals->total_cost;
    }

    public function sales(Carbon $start, Carbon $end): int
    {
        return $this->salesQuery($start, $end)->count();
    }

    /**
     * Percentage change against the same window one period earlier, 0 when
     * there is nothing to compare with.
     */
    public function percentageChange(float $current, float $previous): int
    {
        return $previous == 0.0 ? 0 : (int) round(($current - $previous) / $previous * 100);
    }

    private function totals(Carbon $start, Carbon $end): object
    {
        return $this->salesQuery($start, $end)
            ->selectRaw('COALESCE(SUM(total_price), 0) as total_price')
            ->selectRaw('COALESCE(SUM(tax_price), 0) as tax_price')
            ->selectRaw('COALESCE(SUM(total_discount_per_item), 0) as total_discount_per_item')
            ->selectRaw('COALESCE(SUM(discount_price), 0) as discount_price')
            ->selectRaw('COALESCE(SUM(total_cost), 0) as total_cost')
            ->first();
    }

    /**
     * Paid sales recorded in the window. `created_at` is when the till
     * recorded the sale, which is what the web dashboard already uses.
     */
    private function salesQuery(Carbon $start, Carbon $end): Builder
    {
        return Selling::query()
            ->isPaid()
            ->whereBetween('created_at', [$start->copy()->setTimezone('UTC'), $end->copy()->setTimezone('UTC')]);
    }
}
