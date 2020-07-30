<?php

namespace App\Abstracts;

use Illuminate\Contracts\Support\Responsable;
use Yajra\DataTables\DataTables;

abstract class LaTable implements Responsable
{
    private $query;

    /**
    * Raw Columns For
    * @var rawColumns
    */
    private $rawColumns = [];

    /**
    * Raw Columns For
    * @var defaultRawColumns
    */
    private $defaultRawColumns = [
        'checkbox', 'created_at'
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
                return view('partials.table.date')->with('date', $model->created_at);
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->setRowAttr([
                'style' => 'cursor:pointer'
            ])
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
