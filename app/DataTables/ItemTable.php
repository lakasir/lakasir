<?php

namespace App\DataTables;

use App\Abstracts\LaTable;
use Carbon\Carbon;

class ItemTable extends LaTable
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
            ->addColumn('last_stock', function ($model)
            {
                return $model->last_stock . ' - ' . $model->unit_name;
            })
            ->addColumn('internal_production', function ($model)
            {
                return $model->internal_production ? __('app.global.yes') : __('app.global.no');
            })
            ->addColumn('category_name', function ($model)
            {
                return $model->category_name;
            })
            ->addColumn('initial_price', function ($model)
            {
                return price_format($model->initial_price);
            })
            ->addColumn('selling_price', function ($model)
            {
                return price_format($model->selling_price);
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->addColumn('name', function ($model) {
                return $model->name;
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
