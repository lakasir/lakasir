<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(Category::filter($request)->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category = new Category();
        $category->fill($request->all());
        $category->save();

        return $this->success([], "success creating items");
    }

    public function show(Category $category)
    {
        return $this->success($category);
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category->fill($request->all());
        $category->update();

        return $this->success([], "success updating items");
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->success([], "success deleting items");
    }
}
