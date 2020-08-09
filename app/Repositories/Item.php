<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Category;
use App\Models\Item as ItemModel;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class Item extends RepositoryAbstract
{
    protected string $model = 'App\Models\Item';

    public function datatable(Request $request)
    {
        $items = $this->model::toBase()->addSelect([
            'unit_name' => Unit::select('name')->whereColumn('unit_id', 'units.id')->latest()->limit(1),
            'category_name' => Category::select('name')->whereColumn('category_id', 'categories.id')->latest()->limit(1),
            'initial_price' => Price::select('initial_price')->whereColumn('item_id', 'items.id')->latest()->limit(1),
            'selling_price' => Price::select('selling_price')->whereColumn('item_id', 'items.id')->latest()->limit(1),
        ])->latest()->get();

        return $this->getObjectModel()->table($items);
    }

    public function create(Request $request): ItemModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            $request->merge([
                'date' => now()->format('Y-m-d'),
                'current_stock' => $request->stock,
                'last_stock' => $request->stock
            ]);
            $item = new $self->model();
            $item->fill($request->only('internal_production', 'name'));
            $item->unit()->associate(Unit::find($request->unit_id));
            $item->category()->associate(Category::find($request->category_id));
            $item->save();
            $item->createMediaFromFile($request->image);

            // create price
            $price = new Price();
            $price->fill($request->only('initial_price', 'selling_price', 'date'));
            $price->item()->associate($item);
            $price->save();

            // create stock
            $stock = new Stock();
            $stock->fill($request->only('current_stock', 'last_stock', 'date'));
            $stock->item()->associate($item);
            $stock->save();

            return $item;
        });
    }

    public function update(Request $request, $item): ItemModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self, $item) {
            $request->merge([
                'date' => now()->format('Y-m-d'),
                'current_stock' => $request->stock,
                'last_stock' => $request->stock,
            ]);
            if (!$request->internal_production) {
                $request->merge(['internal_production' => false]);
            }
            $item->fill($request->only('internal_production', 'name'));
            $item->unit()->associate(Unit::find($request->unit_id));
            $item->category()->associate(Category::find($request->category_id));
            $item->save();
            // delete image
            if ($request->hasFile('image')) {
                $item->deleteMedia($item->media->first())->createMediaFromFile($request->image);
            }

            return $item;
        });
    }
}
