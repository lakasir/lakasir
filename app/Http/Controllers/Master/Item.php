<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ItemDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Item\BulkDelete;
use App\Http\Requests\Master\Item\Delete;
use App\Http\Requests\Master\Item\Index;
use App\Http\Requests\Master\Item\Store;
use App\Http\Requests\Master\Item\Update;
use App\Models\Category;
use App\Models\Unit;
use App\Repositories\Item as ItemRepository;
use App\Traits\HasCrudActions;
use Illuminate\View\View;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        get_lang();
        $this->authorize("create-$this->permission");
        $categories = Category::toBase()->get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        });
        $units = Unit::toBase()->get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        });

        return view("{$this->viewPath}.create", compact('categories', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $model): View
    {
        get_lang();
        $data = $this->repository->find($model);

        $this->authorize("update-$this->permission");

        $categories = Category::toBase()->get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        });

        $units = Unit::toBase()->get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        });

        return view("{$this->viewPath}.edit", compact('categories', 'data', 'units'));
    }
}
