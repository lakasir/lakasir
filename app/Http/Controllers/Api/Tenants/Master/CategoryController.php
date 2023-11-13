<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
            ->allowedFilters(['name'])
            ->get();

        return $this->buildResponse()
            ->setData(new CategoryCollection($categories))
            ->present();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category = new Category();
        $category->fill($request->all());
        $category->save();

        return $this->buildResponse()
            ->setMessage('success creating category')
            ->present();
    }

    public function show(Category $category)
    {
        return $this->buildResponse()
            ->setData($category)
            ->present();
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $category->fill($request->all());
        $category->update();

        return $this->buildResponse()
            ->setMessage('success updating category')
            ->present();
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->buildResponse()
            ->setMessage('success deleting category')
            ->present();
    }
}
