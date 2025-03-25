<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivableItem;
use App\Models\Tenants\Selling;
use Filament\Facades\Filament;

class ReceivableService
{
    public function create(Selling $selling, array $data = [])
    {
        $receivable = new Receivable();
        $receivable->fill([
            'total_receivable' => $selling->total_price,
            'rest_receivable' => $selling->total_price - $selling->payed_money,
            'due_date' => $data['due_date'] ?? now()->addWeek(2),
        ]);
        $receivable->selling()->associate($selling);
        $receivable->member()->associate($selling->member);
        $receivable->save();

        collect($data['products'])->each(function ($item) use ($receivable) {
            $receivableItem = new ReceivableItem();
            $receivableItem->fill([
                'price' => $item['price'] / $item['qty'],
                'subtotal' => $item['price'],
                'amount' => $item['qty'],
            ]);
            $receivableItem->product()->associate(Product::find($item['product_id']));
            $receivableItem->receivable()->associate($receivable);
            $receivableItem->save();
        });

        if ($selling->payed_money != 0) {
            $receivable->last_billing_date = $selling->date;
            $receivable->save();
            $receivable->receivablePayments()->create([
                'payment_method_id' => $selling->payment_method_id,
                'amount' => $selling->payed_money,
                'date' => $selling->date,
                'user_id' => Filament::auth()->id(),
                'last_receivable' => $receivable->rest_receivable,
                'receivable_id' => $receivable->getKey(),
            ]);
        }
    }
}
