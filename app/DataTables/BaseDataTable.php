<?php

namespace App\DataTables;

use App\Interfaces\Button as InterfacesButton;
use App\Interfaces\Columns;
use App\Interfaces\Options;
use Carbon\Carbon;
use Illuminate\View\View;
use Yajra\DataTables\Services\DataTable;

/**
 * Class BaseDataTable
 * @author sheenazien8
 */
abstract class BaseDataTable extends DataTable implements Options, Columns, InterfacesButton
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function ($value)
            {
                $date = (new Carbon($value->created_at))->diffForHumans();

                return $date;
            })
            ->addColumn('action', function ($row)
            {
                return $this->addActions($row);
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('customertype-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->buttons($this->getButton());
    }

    /** @return string|View  */
    private function addActions($row)
    {
        return view('partials.table.action', ['actions' => $this->addOptionsBuilder($row)]);
    }
}
