<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\BulkDelete;
use App\Http\Requests\Transaction\Purchasing\Index;
use App\Http\Requests\Transaction\Purchasing\Store;
use App\Http\Requests\Transaction\Purchasing\Update;
use App\Repositories\Item;
use App\Repositories\Purchasing as PurchasingRepository;
use App\Repositories\Supplier;
use App\Services\PurchasingService;
use App\Traits\HasCrudActions;
use Illuminate\View\View;

class Purchasing extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.transaction.purchasings';

    protected $permission = 'purchasing';

    protected $indexRequest = Index::class;

    protected $storeRequest = Store::class;

    protected $updateRequest = Update::class;

    protected $bulkDestroyRequest = BulkDelete::class;

    protected $redirect = '/transaction/purchasing';

    protected $repositoryClass = PurchasingRepository::class;

    protected $storeService = [PurchasingService::class, 'create'];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        app()->setLocale(optional(auth()->user() ?? 'en')->localization);

        $this->authorize("create-$this->permission");

        $options = collect();
        $options->put('Supplier', (new Supplier)->getModel()::get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        }));
        $options->put('PaymentMethod', collect(config('array_options.payment_method'))->map(function ($c) {
            return ['id' => $c, 'text' => dash_to_space($c)];
        }));
        $options->put('Item', (new Item)->getModel()::get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        }));

        return view("{$this->viewPath}.create", compact('options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $model): View
    {
        app()->setLocale(optional(auth()->user() ?? 'en')->localization);

        $data = $this->repository->find($model);

        $this->authorize("update-$this->permission");

        $options->put('Supplier', (new Supplier)->getModel()::get()->map(function ($c) {
            return ['id' => $c->id, 'text' => $c->name];
        }));
        $options->put('PaymentMethod', collect(config('array_options.payment_method'))->map(function ($c) {
            return ['id' => $c, 'text' => dash_to_space($c)];
        }));

        return view("{$this->viewPath}.edit", compact('options', 'data'));
    }
}
