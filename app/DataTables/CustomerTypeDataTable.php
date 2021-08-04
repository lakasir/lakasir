<?php

namespace App\DataTables;

use App\Models\CustomerType;
use Yajra\DataTables\Html\Column;

class CustomerTypeDataTable extends BaseDataTable
{
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CustomerType $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CustomerType $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('name')->title(trans('app.customer_types.column.name')),
            Column::make('default_point')->title(trans('app.customer_types.column.default_point')),
            Column::make('created_at')->title(trans('app.global.created_at')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'CustomerType_' . date('YmdHis');
    }
}
