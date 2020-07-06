<?php

Namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Category;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Item extends RepositoryAbstract
{
    protected string $model = 'App\Models\Item';

    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function() use ($request, $self) {
            $request->merge([
                'date' => now()->format('Y-m-d'),
                'current_stock' => $request->stock,
                'last_stock' => $request->stock,
            ]);
            $item = new $self->model();
            $item->fill($request->only('internal_production', 'name'));
            $item->unit()->associate(Unit::find($request->unit_id));
            $item->category()->associate(Category::find($request->category_id));
            $item->save();
            $item->createMediaFromFile($request->image);
            $price = new Price();
            $price->fill($request->only('initial_price', 'selling_price', 'date'));
            $price->item()->associate($item);
            $price->save();
            $stock = new Stock();
            $stock->fill($request->only('current_stock', 'last_stock', 'date'));
            $stock->item()->associate($item);
            $stock->save();

            return $item;
        });
    }

    public function update(Request $request, $item)
    {
        $self = $this;
        return DB::transaction(static function() use ($request, $self, $item) {
            $request->merge([
                'date' => now()->format('Y-m-d'),
                'current_stock' => $request->stock,
                'last_stock' => $request->stock,
            ]);
            $item->fill($request->only('internal_production', 'name'));
            $item->unit()->associate(Unit::find($request->unit_id));
            $item->category()->associate(Category::find($request->category_id));
            $item->save();
            if ($request->hasFile('image')) {
                $item->deleteMedia($item->media->first())->createMediaFromFile($request->image);
            }

            return $item;
        });
    }


}
