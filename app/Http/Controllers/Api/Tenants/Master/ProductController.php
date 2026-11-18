<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Filters\ComparisonFilter;
use App\Http\Controllers\Controller;
use App\Http\Filters\SearchFields;
use App\Http\Requests\Tenants\Master\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Tenants\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->resolvePerPage($request);

        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                'name',
                'category_id',
                'sellingPrice',
                'initialPrice',
                'type',
                'category.name',
                'unit',
                'show',
                ...ComparisonFilter::setFilters('stock', ['gt', 'ge', 'lt', 'le', 'eq', 'ne']),
                AllowedFilter::custom('global', new SearchFields, 'name,sku,barcodes.code'),
            ])
            ->allowedIncludes(['category', 'images'])
            ->orderByDesc('created_at')
            ->simplePaginate($perPage ?? (new Product())->getPerPage());

        return $this->buildResponse()
            ->setData(ProductCollection::collection($products))
            ->present();
    }

    public function store(ProductRequest $request)
    {
        $request->created();

        return $this->buildResponse()
            ->setMessage('success creating items')
            ->present();
    }

    public function show($product)
    {
        $modelById = Product::find($product);
        $modelByCode = Product::findByBarcodeOrSku($product);

        if ($modelById && $modelByCode && $modelById->getKey() !== $modelByCode->getKey()) {
            abort(409, 'Ambiguous product identifier');
        }

        $model = $modelById ?? $modelByCode;
        if (! $model) {
            abort(404, 'Product not found');
        }

        $model->load(['category', 'stocks']);
        $model = new ProductCollection($model);

        return $this->buildResponse()
            ->setData($model)
            ->present();
    }

    public function update(ProductRequest $request)
    {
        $request->updated();

        return $this->buildResponse()
            ->setMessage('success updating items')
            ->present();
    }

    public function destroy(Product $product, ProductRequest $request)
    {
        $request->deleteImages();
        $product->delete();

        return $this->buildResponse()
            ->setMessage('success deleting items')
            ->present();
    }
}
