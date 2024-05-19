<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtItem;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;

class DebtService
{
    public function create(Selling $selling, array $data = [])
    {
        $debt = new Debt();
        $debt->fill([
            'total_debt' => $selling->total_price,
            'rest_debt' => $selling->total_price - $selling->payed_money,
            'due_date' => $data['due_date'] ?? now()->addWeek(2),
        ]);
        $debt->selling()->associate($selling);
        $debt->member()->associate($selling->member);
        $debt->save();

        $selling->sellingDetails->each(function (SellingDetail $sellingDetail) use ($debt) {
            $debtItem = new DebtItem();
            $debtItem->fill([
                'price' => $sellingDetail->product->selling_price,
                'subtotal' => $sellingDetail->product->selling_price * $sellingDetail->qty,
                'amount' => $sellingDetail->qty,
            ]);
            $debtItem->product()->associate($sellingDetail->product);
            $debtItem->debt()->associate($debt);
            $debtItem->save();
        });
    }
}
