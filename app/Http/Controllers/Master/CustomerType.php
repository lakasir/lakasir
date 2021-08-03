<?php

namespace App\Http\Controllers\Master;

use App\Models\CustomerType as CustomerTypeModel;
use App\Http\Requests\Master\CustomerType\Browse;
use App\Http\Requests\Master\CustomerType\BulkDelete;
use App\Http\Requests\Master\CustomerType\Destroy;
use App\Http\Requests\Master\CustomerType\Create;
use App\Http\Requests\Master\CustomerType\Update;
use App\Services\CustomerType as CustomerTypeService;
use App\Traits\CustomerType\CustomerTypeTrait;
use Illuminate\View\View;

class CustomerType
{
    use CustomerTypeTrait;

    private $viewPath = 'app.master.customer_types';

    /**
     * Display a listing of the resource.
     *
     * @param Browse $request
     * @param CustomerTypeService $customerTypeService
     * @return mix
     */
    public function index(Browse $request, CustomerTypeService $customerTypeService)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $customerTypeService->datatable($request);
        }

        return view("{$this->viewPath}.index", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Create $request
     * @return View
     * @throws BindingResolutionException
     */
    public function create(Create $request): View
    {
        return view("{$this->viewPath}.create", [
            'resources' => $this->resources()
        ]);
    }

    /**
     * @param Create $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request)
    {
        CustomerTypeModel::create($request->all());

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param CustomerTypeModel $customerType
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(CustomerTypeModel $customerType, Browse $request)
    {
        $data = $customerType;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param CustomerTypeModel $customerType
     * @param ItemRepository $customerTypeService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(CustomerTypeModel $customerType, Update $request)
    {
        $data = $customerType;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param CustomerTypeModel $customerType
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(CustomerTypeModel $customerType, CustomerTypeService $customerTypeService, Update $request)
    {
        $customerType->update($request->all());

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param CustomerTypeModel $customerType
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(CustomerTypeModel $customerType, Destroy $request)
    {
        $customerType->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param CustomerTypeService $customerTypeService
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, CustomerTypeService $customerTypeService)
    {
        $customerTypeService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

}
