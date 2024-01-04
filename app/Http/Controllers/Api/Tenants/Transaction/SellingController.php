<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionSellingStoreRequest;
use App\Models\Tenants\Selling;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SellingController extends Controller
{
    public function index(Request $request)
    {
        $sellings = QueryBuilder::for(Selling::class)
            ->allowedFilters([
                'code',
                'member_id',
                'date',
                'code',
                'payed_money',
                'money_changes',
                'total_price',
                'total_qty',
                'created_at',
                'updated_at',
                'sellingDetails.product_id',
            ])
            ->defaultSort('-created_at')
            ->simplePaginate($request->get('per_page', 10));

        return $this->buildResponse()
            ->setData($sellings)
            ->setMessage('success get sellings')
            ->present();
    }

    public function store(TransactionSellingStoreRequest $request)
    {
        $request->store();

        return $this->success([], "success creating items");
    }

    public function show(Selling $selling)
    {
        return $this->success($selling);
    }
}
