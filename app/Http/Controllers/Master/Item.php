<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\Item\BulkDelete;
use App\Http\Requests\Master\Item\Browse;
use App\Http\Requests\Master\Item\Create;
use App\Http\Requests\Master\Item\Destroy;
use App\Http\Requests\Master\Item\Update;
use App\Repositories\Item as ItemRepository;
use App\Traits\Item\ItemTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
    public function index(Browse $request, ItemRepository $itemRepository)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $itemRepository->datatable($request);
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
     * @param ItemRepository $itemRepository
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request, ItemRepository $itemRepository)
    {
        $itemRepository->create($request);

        $message = __('hascrudactions::app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);
        return redirect()->to(route("{$this->resources()}.index"))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }

    /**
     * @param mixed $model
     * @param ItemRepository $itemRepository
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, ItemRepository $itemRepository, Browse $request)
    {
        $data = $itemRepository->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param ItemRepository $itemRepository
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, ItemRepository $itemRepository, Update $request)
    {
        $data = $itemRepository->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param ItemRepository $itemRepository
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, ItemRepository $itemRepository, Update $request)
    {
        $data = $itemRepository->find($model);

        $data = $itemRepository->update($request, $data);

        $message = __('hascrudactions::app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        return redirect()->to(route("{$this->resources()}.index"))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }

    /**
     * @param mixed $model
     * @param ItemRepository $itemRepository
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, ItemRepository $itemRepository, Destroy $request)
    {
        $data = $itemRepository->find($model);

        $data->delete();

        $message = __('hascrudactions::app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        return redirect()->to(route("{$this->resources()}.index"))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }

    /**
     * @param BulkDelete $request
     * @param ItemRepository $itemRepository
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, ItemRepository $itemRepository)
    {
        $itemRepository->bulkDestroy($request);

        $message = __('hascrudactions::app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        return redirect()->to(route("{$this->resources()}.index"))->with('message', [
            'success' => dash_to_space($message)
        ]);
    }
}
