<?php

namespace App\Interfaces;

/**
 * Interface WithCustomColumn
 * @author sheenazien8
 */
interface WithCustomColumn
{
    /**
     * @param \Yajra\DataTables\DataTableAbstract $datatbale
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function customColumn($datatbale);
}
