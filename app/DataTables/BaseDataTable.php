<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\View\View;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Button;

/**
 * Class BaseDataTable
 * @author sheenazien8
 */
abstract class BaseDataTable extends DataTable
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
            ->addColumn('action', $this->addActions());
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
                    ->buttons(
                        Button::make('create')->text('<i class="fa fa-plus"></i> '. __('app.global.create')),
                        Button::make('reload')->text('<i class="fas fa-sync"></i> '. __('app.global.reload')),
                        /* Button::make('bulkDelete')->text('<i class="fa fa-trash"></i> '. __('app.global.bulk-delete')), */
                    );
    }

    /** @return string|View  */
    protected function addActions()
    {
        return 'partials.table.action';
    }
}
