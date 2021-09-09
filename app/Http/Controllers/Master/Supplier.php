<?php

namespace App\Http\Controllers\Master;

use App\DataTables\SupplierDataTable;
use App\Http\Requests\Master\Supplier\BulkDelete;
use App\Http\Requests\Master\Supplier\Browse;
use App\Http\Requests\Master\Supplier\Store;
use App\Http\Requests\Master\Supplier\Destroy;
use App\Http\Requests\Master\Supplier\Update;
use App\Models\Supplier as SupplierModel;
use App\Services\Supplier as SupplierService;
use App\Traits\Supplier\SupplierTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\View\View;

class Supplier
{
    use SupplierTrait;

    private $viewPath = 'app.master.suppliers';

    /**
     * @param Browse $request
     * @param SupplierDataTable $dataTable
     * @return mixed
     * @throws BindingResolutionException
     */
    public function index(Browse $request, SupplierDataTable $dataTable)
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
     * @param SupplierModel $supplier
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(SupplierModel $supplier, Browse $request): View
    {
        $data = $supplier;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param SupplierModel $supplier
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(SupplierModel $supplier, Update $requestModal)
    {
        $data = $supplier;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param SupplierModel $supplier
     * @param SupplierService $supplierService
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(SupplierModel $supplier, SupplierService $supplierService, Update $request)
    {
        $supplierService->update($request, $supplier);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param SupplierModel $supplier
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(SupplierModel $supplier, Destroy $request)
    {
        $supplier->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param SupplierService $supplierService
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
