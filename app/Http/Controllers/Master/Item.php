<?php

namespace App\Http\Controllers\Master;

use App\DataTables\ItemDataTable;
use App\Http\Requests\Master\Item\Browse;
use App\Http\Requests\Master\Item\Create;
use App\Http\Requests\Master\Item\Update;
use App\Http\Requests\Master\Item\UpdateStockRate;
use App\Traits\Item\ItemTrait;
use Illuminate\View\View;
use App\Models\Item as ModelsItem;
use App\Services\Item as ServicesItem;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;

/** @package App\Http\Controllers\Master */
class Item
{
    use ItemTrait;

    private $viewPath = 'app.master.items';


    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, ItemDataTable $datatable)
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
    public function store(Create $request, ServicesItem $item)
    {
        try {
            $item->create($request);

            $message = __('app.global.message.success.create', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->success($message);

            return redirect()->to(route("{$this->resources()}.index"));
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * @param ModelsItem $item
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(ModelsItem $item, Browse $request)
    {
        $data = $item;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsItem $item
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(ModelsItem $item, Update $request)
    {
        $data = $item;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsItem $item
     * @param Update $request
     * @param ServicesItem $servicesItem
     * @return RedirectResponse
     */
    public function update(ModelsItem $item, Update $request, ServicesItem $servicesItem)
    {
        try {
            $servicesItem->update($request, $item);

            $message = __('app.global.message.success.update', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->success($message);

        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput($request->all());
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param ModelsItem $item
     * @param UpdateStockRate $request
     * @param ServicesItem $servicesItem
     * @return RedirectResponse
     */
    public function updateStockRate(ModelsItem $item, UpdateStockRate $request, ServicesItem $servicesItem)
    {
        try {
            $servicesItem->updateStockRate($request, $item);

            $message = __('app.global.message.success.update', [
                'item' => ucfirst($this->resources())
            ]);

            flash()->success($message);
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return redirect()->back()->withInput($request->all());
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

}
