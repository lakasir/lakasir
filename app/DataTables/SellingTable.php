<?php

namespace App\DataTables;

use App\Abstracts\LaTable;
use Carbon\Carbon;

class SellingTable extends LaTable
{
    public function newTable()
    {
        return datatables($this->query)
            ->addColumn('checkbox', function ($model) {
                return view('partials.table.checkbox', compact('model'));
            })
            ->addColumn('created_at', function ($model) {
                $date = (new Carbon($model->created_at))->diffForHumans();
                return view('partials.table.date')->with('date', $date);
            })
            ->addColumn('user', function ($model) {
                return $model->user->username;
            })
            ->addColumn('customer', function ($model) {
                return optional($model->customer ?? '')->name;
            })
            ->addColumn('payment_method', function ($model) {
                return $model->paymentMethod->name;
            })
            ->addColumn('money', function ($model) {
                return price_format($model->money);
            })
            ->addColumn('refund', function ($model) {
                return price_format($model->refund);
            })
            ->addColumn('total_price', function ($model) {
                return price_format($model->total_price);
            })
            ->addColumn('total_profit', function ($model) {
                return price_format($model->total_profit);
            })
            ->addColumn('total_qty', function ($model) {
                return $model->total_qty;
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->rawColumns(array_merge($this->defaultRawColumns, $this->rawColumns));
    }
}
