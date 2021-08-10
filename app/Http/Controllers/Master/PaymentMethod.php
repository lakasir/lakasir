<?php

namespace App\Http\Controllers\Master;

use App\DataTables\PaymentMethodDataTable;
use App\Http\Requests\Master\PaymentMethod\Browse;
use App\Http\Requests\Master\PaymentMethod\BulkDelete;
use App\Http\Requests\Master\PaymentMethod\Create;
use App\Http\Requests\Master\PaymentMethod\Destroy;
use App\Http\Requests\Master\PaymentMethod\Update;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Services\PaymentMethod as PaymentMethodService;
use App\Traits\PaymentMethod\PaymentMethodTrait;
use Illuminate\View\View;

/** @package App\Http\Controllers\Master */
class PaymentMethod
{
    use PaymentMethodTrait;

    private $viewPath = 'app.master.payment_methods';

    /**
     * Display a listing of the resource.
     *
     * @param Browse $request
     * @param PaymentMethodDataTable $dataTable
     * @return mix
     */
    public function index(Browse $request, PaymentMethodDataTable $dataTable)
    {
        return $dataTable->render("{$this->viewPath}.index", [
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
        PaymentMethodModel::create($request->all());

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param PaymentMethodModel $paymentMethod
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(PaymentMethodModel $paymentMethod, Browse $request)
    {
        $data = $paymentMethod;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param PaymentMethodModel $paymentMethod
     * @param ItemRepository $paymentMethodService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(PaymentMethodModel $paymentMethod, Update $request)
    {
        $data = $paymentMethod;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update(PaymentMethodModel $paymentMethod, Update $request)
    {
        $paymentMethod->update($request->all());

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param PaymentMethodModel $paymentMethod
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(PaymentMethodModel $paymentMethod, Destroy $request)
    {
        $paymentMethod->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param PaymentMethodService $paymentMethodService
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, PaymentMethodService $paymentMethodService)
    {
        $paymentMethodService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }


}
