<?php

namespace App\DataTables;

use App\Abstracts\LaTable;
use Carbon\Carbon;

class PaymentMethodTable extends LaTable
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
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->addColumn('visible_in', function ($model) {
                if ($model->visible_in) {
                    $visible_in = json_decode($model->visible_in);
                    $string = '';
                    if (isset($visible_in->purchasing)) {
                        $string .= __('app.purchasings.title');
                    }
                    if (isset($visible_in->selling)) {
                        $string .= ', ' . __('app.sellings.title.cashier');
                    }
                    return $string;
                }
                return __('app.payment_methods.info.visible_in.empty');
            })
            ->addColumn('name', function ($model) {
                return $model->name;
            })
            ->addColumn('action', function ($model) {
                $resources = explode('.', request()->route()->action['as'])[0];
                $action = [
                    'model' => $model
                ];
                if ($model->can_delete) {
                    $action['delete'] = route("{$resources}.destroy", $model->id);
                }
                return view('partials.table.action', $action);
            })
            ->rawColumns(array_merge($this->defaultRawColumns, $this->rawColumns));
    }
}
