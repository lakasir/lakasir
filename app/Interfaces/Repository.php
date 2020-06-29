<?php

namespace App\Interfaces;

interface Repository
{
    public function paginate($request, $columns, $search);

    public function all($columns);

    public function get($request, $columns, $search);

    public function create($request);

    public function update($request, $model);

    public function delete($id);

    public function find($id);

    public function bulkDestroy($request);

}
