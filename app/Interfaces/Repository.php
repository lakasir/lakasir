<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface Repository
{
    public function datatable(Request $request);

    public function paginate(Request $request, array $columns, string $search);

    public function all(Request $request, array $columns, string $search);

    public function get(Request $request, array $columns, string $search);

    public function create(Request $request);

    public function update(Request $request, $model);

    public function delete(int $id);

    public function find(int $id);

    public function bulkDestroy(Request $request, string $column);

    public function findByKeyArray(array $key, string $id = "id");

    public function getModel(): string;

    public function getObjectModel(): Object;
}
