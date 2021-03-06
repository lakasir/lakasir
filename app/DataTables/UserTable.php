<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Sheenazien8\Hascrudactions\Abstracts\LaTable;
use Yajra\DataTables\Services\DataTable;

/** @package App\DataTables */
class UserTable extends LaTable
{
    /**
     * @return DataTable
     * @throws BindingResolutionException
     */
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
            ->addColumn('role', function ($model) {
                return $model->getRoleNames()->first();
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
