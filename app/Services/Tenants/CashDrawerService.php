<?php

namespace App\Services\Tenants;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Selling;
use Illuminate\Support\Collection;

class CashDrawerService
{
    /**
     * Totals for one shift. Money is what was actually received for each paid
     * sale (payed money minus change), grouped by payment method; the cash
     * methods are what should be in the drawer on top of the opening cash.
     */
    public function summary(CashDrawer $cashDrawer): array
    {
        /** @var Collection<int, Selling> $sellings */
        $sellings = Selling::query()
            ->with('paymentMethod')
            ->where('cash_drawer_id', $cashDrawer->id)
            ->isPaid()
            ->get();

        $paymentMethods = $sellings
            ->groupBy('payment_method_id')
            ->map(function (Collection $group): array {
                /** @var ?PaymentMethod $method */
                $method = $group->first()->paymentMethod;

                return [
                    'payment_method_id' => $method?->id,
                    'name' => $method?->name,
                    'is_cash' => $this->isCash($method),
                    'count' => $group->count(),
                    'total' => $group->sum(fn (Selling $selling): float => $this->received($selling)),
                ];
            })
            ->values();

        $openingCash = (float) $cashDrawer->cash;
        $cashSales = (float) $paymentMethods->where('is_cash', true)->sum('total');
        $expectedCash = $openingCash + $cashSales;
        $countedCash = $cashDrawer->counted_cash !== null ? (float) $cashDrawer->counted_cash : null;

        return [
            'id' => $cashDrawer->id,
            'opened_at' => $cashDrawer->created_at,
            'closed_at' => $cashDrawer->closed_at,
            'opened_by' => $cashDrawer->openedBy?->only(['id', 'name']),
            'closed_by' => $cashDrawer->closedBy?->only(['id', 'name']),
            'opening_cash' => $openingCash,
            'cash_sales' => $cashSales,
            'expected_cash' => $expectedCash,
            'counted_cash' => $countedCash,
            'difference' => $countedCash !== null ? $countedCash - $expectedCash : null,
            'sales_count' => $sellings->count(),
            'sales_total' => (float) $paymentMethods->sum('total'),
            'payment_methods' => $paymentMethods->all(),
        ];
    }

    private function received(Selling $selling): float
    {
        return (float) $selling->payed_money - (float) $selling->money_changes;
    }

    private function isCash(?PaymentMethod $method): bool
    {
        return $method !== null
            && $method->is_cash
            && ! $method->is_credit
            && ! $method->is_debit
            && ! $method->is_wallet;
    }
}
