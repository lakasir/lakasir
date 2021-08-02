<?php

namespace App\Http\Controllers\Master;

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
     * @param ItemRepository $customerTypeService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request, CustomerTypeService $customerTypeService)
    {
        $customerTypeService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param ItemRepository $customerTypeService
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, CustomerTypeService $customerTypeService, Browse $request)
    {
        $data = $customerTypeService->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param ItemRepository $customerTypeService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, CustomerTypeService $customerTypeService, Update $request)
    {
        $data = $customerTypeService->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param ItemRepository $customerTypeService
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, CustomerTypeService $customerTypeService, Update $request)
    {
        $data = $customerTypeService->update($request, $data);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param ItemRepository $customerTypeService
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, CustomerTypeService $customerTypeService, Destroy $request)
    {
        $data = $customerTypeService->find($model);

        $data->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param ItemRepository $customerTypeService
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
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
