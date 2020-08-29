<?php

namespace App\DataTables;

use App\Abstracts\LaTable;
use Carbon\Carbon;

class PurchasingTable extends LaTable
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
            ->addColumn('total_initial_price', function ($model) {
                return price_format($model->total_initial_price);
            })
            ->addColumn('total_selling_price', function ($model) {
                return price_format($model->total_selling_price);
            })
            ->addColumn('paid', function ($model) {
                return view('app.transaction.purchasings.components.partials.paid', ['paid' => $model->is_paid]);
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->addColumn('action', function ($model) {
                $resources = explode('.', request()->route()->action['as'])[0];
                return view('partials.table.action', [
                    'delete' => route("{$resources}.destroy", $model->id),
                    'model' => $model
                ]);
            })
            ->rawColumns(array_merge($this->defaultRawColumns, $this->rawColumns));
    }
}
