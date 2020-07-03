<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Category\BulkDelete;
use App\Http\Requests\Master\Category\Index;
use App\Http\Requests\Master\Category\Store;
use App\Http\Requests\Master\Category\Update;
use App\Models\Category as Model;
use App\Repositories\Category as CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Category extends Controller
{
    /**
     * @var Category
     */
    public CategoryRepository $category;

    /**
     * @param CategoryRepository $category
     */
    public function __construct()
    {
        $this->category = new CategoryRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Index $request): View
    {
        $this->authorize('browse-category');
        $categories = $this->category->paginate($request, ['name'], 'name');

        return view('app.master.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create-category');

        return view('app.master.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Master\Category\Store $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $request): RedirectResponse
    {
        $this->authorize('create-category');
        $this->category->create($request);

        return redirect()->to('/master/category');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Model $category): View
    {
        $this->authorize('browse-category');

        return view('app.master.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Model $category)
    {
        $this->authorize('update-category');

        return view('app.master.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Model $category): RedirectResponse
    {
        $this->authorize('update-category');
        $this->category->update($request, $category);

        return redirect()->to('/master/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $category): RedirectResponse
    {
        $this->authorize('delete-category');
        $category->delete();

        return redirect()->to('/master/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BulkDelete $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(BulkDelete $request): RedirectResponse
    {
        $this->category->bulkDestroy($request);

        return redirect()->back();
    }
}
