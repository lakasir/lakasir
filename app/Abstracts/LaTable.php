<?php

namespace App\Abstracts;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Yajra\DataTables\DataTables;

abstract class LaTable implements Responsable
{
    protected $query;

    /**
    * Raw Columns For
    * @var rawColumns
    */
    protected $rawColumns = [];

    /**
    * Raw Columns For
    * @var defaultRawColumns
    */
    protected $defaultRawColumns = [
        'checkbox', 'created_at', 'action'
    ];

    /**
     * @param $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function create()
    {
        return $this->newTable();
    }

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
            ->addColumn('action', function ($model) {
                $resources = explode('.', request()->route()->action['as'])[0];
                return view('partials.table.action', [
                    'delete' => route("{$resources}.destroy", $model->id),
                    'model' => $model
                ]);
            })
            ->rawColumns(array_merge($this->defaultRawColumns, $this->rawColumns));
    }


    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->create()->toJson();
    }
}
