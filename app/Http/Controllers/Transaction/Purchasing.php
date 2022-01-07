<?php

namespace App\Http\Controllers\Transaction;

use App\DataTables\PurchasingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Purchasing\Browse;
use App\Http\Requests\Transaction\Purchasing\Create;
use App\Traits\PurchasingTrait;
use Illuminate\View\View;

class Purchasing extends Controller
{
    use PurchasingTrait;

    private $viewPath = 'app.transaction.purchasings';

    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, PurchasingDataTable $datatable)
    {
        return $datatable->render("{$this->viewPath}.index", [
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
    public function store(Create $request, ServicesPurchasing $purchasing)
    {
        try {
            $purchasing->create($request);

            $message = __('app.global.message.success.create', [
                'purchasing' => ucfirst($this->resources())
            ]);

            flash()->success($message);

            return redirect()->to(route("{$this->resources()}.index"));
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * @param ModelsPurchasing $purchasing
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(ModelsPurchasing $purchasing, Browse $request)
    {
        $data = $purchasing;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsPurchasing $purchasing
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(ModelsPurchasing $purchasing, Update $request)
    {
        $data = $purchasing;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsPurchasing $purchasing
     * @param Update $request
     * @param ServicesPurchasing $servicesPurchasing
     * @return RedirectResponse
     */
    public function update(ModelsPurchasing $purchasing, Update $request, ServicesPurchasing $servicesPurchasing)
    {
        try {
            $servicesPurchasing->update($request, $purchasing);

            $message = __('app.global.message.success.update', [
                'purchasing' => ucfirst($this->resources())
            ]);

            flash()->success($message);

        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput($request->all());
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

}
