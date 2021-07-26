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
    public function index(Browse $request, CategoryService $categoryService)
    {
        if ($request->ajax() || isset($this->return) && $this->return == 'api') {
            return $categoryService->datatable($request);
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
     * @param ItemRepository $categoryService
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function store(Create $request, CategoryService $categoryService)
    {
        $categoryService->create($request);

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param ItemRepository $categoryService
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show($model, CategoryService $categoryService, Browse $request)
    {
        $data = $categoryService->find($model);

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param mixed $model
     * @param ItemRepository $categoryService
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit($model, CategoryService $categoryService, Update $request)
    {
        $data = $categoryService->find($model);

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param string|int $model
     * @param ItemRepository $categoryService
     * @param Update $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws BindingResolutionException
     */
    public function update($model, CategoryService $categoryService, Update $request)
    {
        $data = $categoryService->find($model);

        $data = $categoryService->update($request, $data);

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param mixed $model
     * @param ItemRepository $categoryService
     * @param Destroy $request
     * @return RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy($model, CategoryService $categoryService, Destroy $request)
    {
        $data = $categoryService->find($model);

        $data->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param BulkDelete $request
     * @param ItemRepository $categoryService
     * @return Sheenazien8\Hascrudactions\Traits\Illuminate\Http\Response
     * @throws BindingResolutionException
     * @throws AuthorizationException
     */
    public function bulkDestroy(BulkDelete $request, CategoryService $categoryService)
    {
        $categoryService->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
