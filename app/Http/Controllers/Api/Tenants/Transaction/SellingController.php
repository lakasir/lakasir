<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\Sellings\TransactionSellingStoreRequest;
use App\Http\Resources\SellingCollection;
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
            ->with(['member', 'paymentMethod', 'sellingDetails.product', 'user'])
            ->isPaid()
            ->defaultSort('-created_at')
            ->simplePaginate($request->get('per_page', 10));

        return $this->buildResponse()
            ->setData(SellingCollection::collection($sellings))
            ->setMessage('success get sellings')
            ->present();
    }

    public function store(TransactionSellingStoreRequest $request)
    {
        $selling = $request->store();
        $selling->load(['member', 'paymentMethod', 'sellingDetails.product', 'user']);

        return $this->buildResponse()
            ->setMessage('success create selling')
            ->setData(new SellingCollection($selling))
            ->present();
    }

    public function show(Selling $selling)
    {
        $selling->load(['member', 'paymentMethod', 'sellingDetails', 'user']);

        return $this->buildResponse()
            ->setData(new SellingCollection($selling))
            ->setMessage('success get selling')
            ->present();
    }
}
