<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Customer\BulkDelete;
use App\Http\Requests\Master\Customer\Browse;
use App\Http\Requests\Master\Customer\Store;
use App\Http\Requests\Master\Customer\Destroy;
use App\Http\Requests\Master\Customer\Update;
use App\Repositories\Customer as CustomerRepository;
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
    public function index(Browse $request, CustomerRepository $itemRepository)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $itemRepository->datatable($request);
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
     * @param CustomerRepository $itemRepository
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Store $request, CustomerRepository $itemRepository)
    {
        $itemRepository->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param CustomerRepository $itemRepository
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, CustomerRepository $itemRepository, Browse $request)
    {
        $data = $itemRepository->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param CustomerRepository $itemRepository
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, CustomerRepository $itemRepository, Update $request)
    {
		dd($model);
        $data = $itemRepository->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param CustomerRepository $itemRepository
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, CustomerRepository $itemRepository, Update $request)
    {
        $data = $itemRepository->find($model);

        $data = $itemRepository->update($request, $data);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param CustomerRepository $itemRepository
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, CustomerRepository $itemRepository, Destroy $request)
    {
        $data = $itemRepository->find($model);

        $data->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param CustomerRepository $itemRepository
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, CustomerRepository $itemRepository)
    {
        $itemRepository->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
