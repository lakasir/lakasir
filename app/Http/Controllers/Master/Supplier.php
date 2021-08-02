<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Supplier\BulkDelete;
use App\Http\Requests\Master\Supplier\Browse;
use App\Http\Requests\Master\Supplier\Store;
use App\Http\Requests\Master\Supplier\Destroy;
use App\Http\Requests\Master\Supplier\Update;
use App\Services\Supplier as SupplierService;
use App\Traits\Supplier\SupplierTrait;
use Illuminate\View\View;

class Supplier extends Controller
{
    use SupplierTrait;

	private $viewPath = 'app.master.suppliers';

	/**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, SupplierService $supplierService)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $supplierService->datatable($request);
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
     * @param SupplierService $supplierService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Store $request, SupplierService $supplierService)
    {
        $supplierService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param SupplierService $supplierService
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, SupplierService $supplierService, Browse $request)
    {
        $data = $supplierService->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param SupplierService $supplierService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, SupplierService $supplierService, Update $request)
    {
        $data = $supplierService->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param SupplierService $supplierService
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, SupplierService $supplierService, Update $request)
    {
        $data = $supplierService->find($model);

        $data = $supplierService->update($request, $data);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param SupplierService $supplierService
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, SupplierService $supplierService, Destroy $request)
    {
        $data = $supplierService->find($model);

        $data->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param SupplierService $supplierService
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, SupplierService $supplierService)
    {
        $supplierService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
