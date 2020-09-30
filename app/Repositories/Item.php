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
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class Item extends RepositoryAbstract
{
    protected string $model = 'App\Models\Item';

    public function datatable(Request $request)
    {
        $items = $this->query()->toBase()->addSelect([
            'unit_name' => Unit::select('name')->whereColumn('unit_id', 'units.id')->latest()->limit(1),
            'category_name' => Category::select('name')->whereColumn('category_id', 'categories.id')->latest()->limit(1),
            'initial_price' => Price::select('initial_price')->whereColumn('item_id', 'items.id')->orderBy('date', 'asc')->limit(1),
            'selling_price' => Price::select('selling_price')->whereColumn('item_id', 'items.id')->orderBy('date', 'asc')->limit(1),
            'last_stock' => Stock::select(DB::raw('(CASE WHEN (SUM(amount) > 0) THEN SUM(amount) ELSE 0 END)'))->whereColumn('item_id', 'items.id')->latest()->limit(1)
        ])->latest()->get();

        return $this->getObjectModel()->table($items);
    }

    /**
     * store item
     *
     * @return App\Models\Item as ItemModel
     */
    public function create(Request $request): ItemModel
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            $request->merge([
                'date' => now()->format('Y-m-d'),
                'amount' => $request->stock,
            ]);
            $item = new $self->model();
            $item->fill($request->only('internal_production', 'name'));
            $item->unit()->associate(Unit::find($request->unit_id));
            $item->category()->associate(Category::find($request->category_id));
            $item->save();
            $item->createMediaFromFile($request->image);

            $checkPrice = array_must_same(
                $request->only('initial_price', 'selling_price'), [
                'initial_price',
                'selling_price'
            ], 0);

            if (!$checkPrice) {
                // create price
                $price = new Price();
                $price->fill($request->only('initial_price', 'selling_price', 'date'));
                $price->item()->associate($item);
                $price->save();
            }

            $checkStock = array_must_same(
                $request->only('amount'), [
                'amount',
            ], 0);

            if (!$checkStock) {
                // create stock
                $stock = new Stock();
                $stock->fill($request->only('amount', 'date'));
                $stock->item()->associate($item);
                $stock->price()->associate($item);
                $stock->save();
            }

            return $item;
        });
    }

    /**
     * update item
     *
     * @return App\Models\Item as ItemModel
     */
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

    public function totalPriceByRequest(array $items, string $key = 'selling_price'): ?float
    {
        $self = $this;
        $itemPrice = array_map( function($el) use ($self, $key)
        {
            $item = $self->find($el['id']);
            if ($item) {
                $lastPrice = $item->last_price->{$key} * $el['qty'];

                return $lastPrice;
            }
        }, $items);

        return array_sum($itemPrice);
    }

    public function getNewestOrder(): Collection
    {
        return $this->query()
             ->whereHas('sellingDetails.selling', function ($query)
             {
                 return $query->whereTransactionDate(now()->format('Y-m-d'));
             })->with(['sellingDetails' => function ($sellingDetails)
             {
                 return $sellingDetails->whereHas('selling', function ($query)
                 {
                     return $query->whereTransactionDate(now()->format('Y-m-d'));
                 });
             }])->limit(5)->get();
    }

}
