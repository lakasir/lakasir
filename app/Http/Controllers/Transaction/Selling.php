<?php

namespace App\Http\Controllers\Transaction;

use App\Facades\Response;
use App\Exceptions\ServiceActionsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Selling\BulkDelete;
use App\Http\Requests\Transaction\Selling\Index;
use App\Http\Requests\Transaction\Selling\Store;
use App\Http\Requests\Transaction\Selling\Update;
use App\Repositories\Item;
use App\Repositories\PaymentMethod;
use App\Repositories\Selling as SellingRepository;
use App\Repositories\Supplier;
use App\Services\SellingService;
use App\Traits\HasCrudActions;
use Illuminate\View\View;

class Selling extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.transaction.sellings';

    protected $permission = 'selling';

    protected $indexRequest = Index::class;

    protected $redirect = '/transaction/selling';

    protected $repositoryClass = SellingRepository::class;

    protected $showService = [ SellingService::class, 'detail' ];

    public function index()
    {
        get_lang();

        $request = resolve($this->indexRequest);

        if ($this->permission) {
            $this->authorize("browse-$this->permission");
        }

        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $this->repository->datatable($request);
        }

        $resources = $this->permission;

        if (isset($this->resources)) {
            $resources = $this->resources;
        }

        return view("{$this->viewPath}.index", [
            'resources' => $resources,
            'selling' => $this->repository->card()
        ]);
    }
}

