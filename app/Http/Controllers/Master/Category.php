<?php

namespace App\Http\Controllers\Master;

use App\DataTables\CategoryDataTable;
use App\Http\Requests\Master\Category\BulkDelete;
use App\Http\Requests\Master\Category\Browse;
use App\Http\Requests\Master\Category\Create;
use App\Http\Requests\Master\Category\Destroy;
use App\Http\Requests\Master\Category\Update;
use App\Models\Category as ModelsCategory;
use App\Services\Category as ServicesCategory;
use App\Traits\Category\CategoryTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\View\View;

class Category
{
    use CategoryTrait;

    private $viewPath = 'app.master.categories';


    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index(Browse $request, CategoryDataTable $datatable)
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
    public function store(Create $request)
    {
        $category = ModelsCategory::create($request->all());

        $message = __('app.global.message.success.create', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);
        if ($request->input('from')) {
            return redirect()->to($request->input('from') . "?selected=" . $category->id);
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

    /**
     * @param ModelsCategory $category
     * @param Browse $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(ModelsCategory $category, Browse $request)
    {
        $data = $category;

        return view("{$this->viewPath}.show", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsCategory $category
     * @param Update $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(ModelsCategory $category, Update $request)
    {
        $data = $category;

        return view("{$this->viewPath}.edit", [
            'resources' => $this->resources(),
            'data' => $data
        ]);
    }

    /**
     * @param ModelsCategory $category
     * @param Update $request
     * @return RedirectResponse
     * @throws MassAssignmentException
     * @throws BindingResolutionException
     */
    public function update(ModelsCategory $category, Update $request)
    {
        $category->update($request->all());

        $message = __('app.global.message.success.update', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        if ($request->input('from')) {
            return redirect()->to($request->input('from') . "?selected=" . $category->id);
        }

        return redirect()->to(route("{$this->resources()}.index"));
    }

    public function destroy(ModelsCategory $category, Destroy $request)
    {
        $category->delete();

        $message = __('app.global.message.success.delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }

    public function bulkDestroy(BulkDelete $request, ServicesCategory $category)
    {
        $category->bulkDestroy($request);

        $message = __('app.global.message.success.bulk-delete', [
            'item' => ucfirst($this->resources())
        ]);

        flash()->success($message);

        return redirect()->to(route("{$this->resources()}.index"));
    }
}
