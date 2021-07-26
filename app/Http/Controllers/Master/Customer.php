<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Customer\BulkDelete;
use App\Http\Requests\Master\Customer\Browse;
use App\Http\Requests\Master\Customer\Store;
use App\Http\Requests\Master\Customer\Destroy;
use App\Http\Requests\Master\Customer\Update;
use App\Services\Customer as CustomerService;
use App\Traits\Customer\CustomerTrait;
use Illuminate\View\View;

class Customer extends Controller
{
	use CustomerTrait;

	private $viewPath = 'app.master.customers';

	/**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, CustomerService $customerService)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $customerService->datatable($request);
        }

        return view("{$this->viewPath}.index", [
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
     * @param mixed $model
     * @param CustomerService $customerService
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, CustomerService $customerService, Browse $request)
    {
        $data = $customerService->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param CustomerService $customerService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, CustomerService $customerService, Update $request)
    {
        $data = $customerService->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param CustomerService $customerService
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, CustomerService $customerService, Update $request)
    {
        $data = $customerService->find($model);

        $data = $customerService->update($request, $data);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param CustomerService $customerService
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, CustomerService $customerService, Destroy $request)
    {
        $data = $customerService->find($model);

        $data->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param CustomerService $customerService
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
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
