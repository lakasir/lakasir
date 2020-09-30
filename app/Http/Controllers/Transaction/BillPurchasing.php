<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\Index;
use App\Repositories\Purchasing as PurchasingRepository;
use App\Traits\HasCrudActions;

class BillPurchasing extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.transaction.purchasings';

    protected $permission = 'purchasing';

    protected $indexRequest = Index::class;

    protected $redirect = '/transaction/bill_purchasing';

    protected $repositoryClass = PurchasingRepository::class;

    protected $storeService = [PurchasingService::class, 'create'];

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
            'spending' => $this->repository->card()
        ]);
    }

}
