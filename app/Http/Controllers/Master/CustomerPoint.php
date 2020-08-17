<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CustomerPoint\Store;
use App\Repositories\Customer;
use App\Repositories\CustomerPoint as CustomerPointRepository;
use App\Traits\HasCrudActions;
use Illuminate\Http\RedirectResponse;

class CustomerPoint extends Controller
{
    use HasCrudActions;

    protected $permission = 'customer-point';

    protected $storeRequest = Store::class;

    protected $redirect = '/master/customer';

    protected $repositoryClass = CustomerPointRepository::class;

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        get_lang();

        $request = resolve($this->storeRequest);

        $this->authorize("create-$this->permission");

        $customer = (new Customer())->find($request->customer_id);

        $this->repository->hasParent('customer_id', $customer)->create($request);

        return redirect()->to($this->redirect);
    }
}
