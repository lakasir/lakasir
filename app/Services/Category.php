<?php

namespace App\Services;

use App\Models\Category as ModelsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @package App\Services */
class Category
{
    /** @var ModelsCategory $category*/
    private $category;

    public function __construct()
    {
        $this->category = new ModelsCategory();
    }
    /**
     * Bulk Destroy
     *
     * @param Request $request
     * @param string $column (optional)
     *
     * @return void
     */
    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $self = $this;
        if ($self->category->query()->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->category->query()->whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
