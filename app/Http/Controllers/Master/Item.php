<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ItemDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Item\BulkDelete;
use App\Http\Requests\Master\Item\Delete;
use App\Http\Requests\Master\Item\Index;
use App\Http\Requests\Master\Item\Store;
use App\Http\Requests\Master\Item\Update;
use App\Repositories\Item as ItemRepository;
use App\Traits\HasCrudActions;
use Yajra\DataTables\Html\Builder;

class Item extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.master.items';

    protected $permission = 'item';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/master/item';

    protected $repositoryClass = ItemRepository::class;

    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Index $request, Builder $builder)
    {
        $this->authorize('browse-item');
        if ($request->ajax()) {
            return $this->repository->datatable($request);
        }

        $html = $builder->columns([
            ['data' => 'id', 'footer' => '#', 'title' => '#'],
            ['data' => 'name', 'footer' => __('app.items.column.name'), 'title' => __('app.items.column.name')],
            ['data' => 'internal_production', 'footer' => __('app.items.column.internal_production'), 'title' => __('app.items.column.internal_production')],
            ['data' => 'category_name', 'footer' => __('app.items.column.category.name'), 'title' => __('app.items.column.category.name')],
            ['data' => 'unit_name', 'footer' => __('app.items.column.unit.name'), 'title' => __('app.items.column.unit.name')],
            ['data' => 'initial_price', 'footer' => __('app.items.column.price.initial_price'), 'title' => __('app.items.column.price.initial_price')],
            ['data' => 'selling_price', 'footer' => __('app.items.column.price.selling_price'), 'title' => __('app.items.column.price.selling_price')],
        ]);

        return view("{$this->viewPath}.index", compact('html'));
    }
}
