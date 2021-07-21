<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Category\BulkDelete;
use App\Http\Requests\Master\Category\Browse;
use App\Http\Requests\Master\Category\Create;
use App\Http\Requests\Master\Category\Destroy;
use App\Http\Requests\Master\Category\Update;
use App\Services\Category as CategoryService;
use App\Traits\Category\CategoryTrait;
use Illuminate\View\View;

class Category extends Controller
{
    use CategoryTrait;

    private $viewPath = 'app.master.categories';

    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, CategoryService $itemRepository)
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
    public function store(Create $request, CategoryService $itemRepository)
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
     * @param ItemRepository $itemRepository
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, CategoryService $itemRepository, Browse $request)
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
    public function edit($model, CategoryService $itemRepository, Update $request)
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
    public function update($model, CategoryService $itemRepository, Update $request)
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
     * @param ItemRepository $itemRepository
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, CategoryService $itemRepository, Destroy $request)
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
     * @param ItemRepository $itemRepository
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, CategoryService $itemRepository)
    {
        $itemRepository->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
