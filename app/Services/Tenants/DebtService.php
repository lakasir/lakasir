<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtItem;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use Filament\Facades\Filament;

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

        collect($data['products'])->each(function ($item) use ($debt) {
            $debtItem = new DebtItem();
            $debtItem->fill([
                'price' => $item['price'] / $item['qty'],
                'subtotal' => $item['price'],
                'amount' => $item['qty'],
            ]);
            $debtItem->product()->associate(Product::find($item['product_id']));
            $debtItem->debt()->associate($debt);
            $debtItem->save();
        });

        if ($selling->payed_money != 0) {
            $debt->last_billing_date = $selling->date;
            $debt->save();
            $debt->debtPayments()->create([
                'payment_method_id' => $selling->payment_method_id,
                'amount' => $selling->payed_money,
                'date' => $selling->date,
                'user_id' => Filament::auth()->id(),
                'last_debt' => $debt->rest_debt,
                'debt_id' => $debt->getKey(),
            ]);
        }
    }
}
