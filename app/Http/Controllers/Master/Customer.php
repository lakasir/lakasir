<?php

namespace App\Http\Controllers\Master;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Customer\BulkDelete;
use App\Http\Requests\Master\Customer\Browse;
use App\Http\Requests\Master\Customer\Store;
use App\Http\Requests\Master\Customer\Destroy;
use App\Http\Requests\Master\Customer\Update;
use App\Models\Customer as CustomerModel;
use App\Services\Customer as CustomerService;
use App\Traits\Customer\CustomerTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\View\View;

class Customer extends Controller
{
    use CustomerTrait;

    private $viewPath = 'app.master.customers';

    /**
     * @param Browse $request
     * @param CustomerDataTable $dataTable
     * @return mixed
     * @throws BindingResolutionException
     */
    public function index(Browse $request, CustomerDataTable $dataTable)
    {
        return $dataTable->render("{$this->viewPath}.index", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Store $request
     * @return View
     * @throws BindingResolutionException
     */
    public function create(Store $request): View
    {
        return view("{$this->viewPath}.create", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Store $request
     * @param CustomerService $customerService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Store $request, CustomerService $customerService)
    {
        $customerService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param CustomerModel $customer
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(CustomerModel $customer, Browse $request)
    {
        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $customer
        ]);
    }

    /**
     * @param CustomerModel $customer
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(CustomerModel $customer, Update $request)
    {
        $data = $customer;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param CustomerModel $customer
     * @param Update $request
     * @param CustomerService $customerService
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(CustomerModel $customer, CustomerService $customerService, Update $request)
    {
        $customerService->update($request, $customer);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param CustomerModel $customer
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(CustomerModel $customer, Destroy $request)
    {
        $customer->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param CustomerService $customerService
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, CustomerService $customerService)
    {
        $customerService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
