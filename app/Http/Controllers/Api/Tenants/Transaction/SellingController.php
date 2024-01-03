<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionSellingStoreRequest;
use App\Models\Tenants\Selling;
use Illuminate\Http\Request;

class SellingController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(Selling::filter($request)
            ->orderByDesc('created_at')
            ->get());
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
