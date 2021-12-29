<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item as ItemModel;
use App\Models\Price;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** @package App\Repositories */
class Item
{
    /**
     * create
     *
     * @param Request $request
     * @access public
     * @return ItemModel
     */
    public function create(Request $request): ItemModel
    {
        try {
            DB::beginTransaction();
            $item = new ItemModel();
            $item->fill($request->all());
            $category = Category::find($request->category);
            if (!$category) {
                throw new Exception("Category not found");
            }
            $item->category()->associate($category);
            $item->save();

            // create price
            $price = new Price();
            $price->fill($request->merge([
                'date' => now()->format('Y-m-d')
            ])->all());
            $price->item()->associate($item);
            $price->save();

            // create stocks
            $stock = new Stock();
            $stock->fill($request->merge(['amount' => $request->stock])->all());
            $stock->price()->associate($price);
            $stock->item()->associate($item);
            $stock->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $item;
    }

    /**
     * update
     *
     * @param Request $request
     * @param \App\Models\Item $item
     * @access public
     * @return ItemModel
     */
    public function update(Request $request, $item): ItemModel
    {
        try {
            if (!$item->exists) {
                throw new Exception("Updated item not found");
            }
            DB::beginTransaction();
            $item->fill($request->all());
            $category = Category::find($request->category);
            if (!$category) {
                throw new Exception("Category not found");
            }
            $item->category()->associate($category);
            $item->update();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $item;
    }

    public function updateStockRate(Request $request, ItemModel $item)
    {
        try {
            DB::beginTransaction();
            $item->prices->dd();
            $item->fill($request->all());
            $item->save();
            // create price
            $price = new Price();
            $price->fill($request->merge([
                'date' => now()->format('Y-m-d')
            ])->all());
            $price->item()->associate($item);
            $price->save();

            // create stocks
            $stock = new Stock();
            $stock->fill($request->merge(['amount' => $request->stock])->all());
            $stock->price()->associate($price);
            $stock->item()->associate($item);
            $stock->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $item;
    }

    /**
     * @param array $items
     * @param string $key
     * @return null|float
     */
    public function totalPriceByRequest(array $items, string $key = 'selling_price'): ?float
    {
        $self = $this;
        $itemPrice = array_map(function ($el) use ($self, $key) {
            $item = $self->find($el['id']);
            if ($item) {
                $lastPrice = $item->last_price->{$key} * $el['qty'];

                return $lastPrice;
            }
        }, $items);

        return array_sum($itemPrice);
    }

    /** @return Collection  */
    public function getNewestOrder(): Collection
    {
        return $this->query()
            ->whereHas('sellingDetails.selling', function ($query) {
                return $query->whereTransactionDate(now()->format('Y-m-d'));
            })->with(['sellingDetails' => function ($sellingDetails) {
                return $sellingDetails->whereHas('selling', function ($query) {
                    return $query->whereTransactionDate(now()->format('Y-m-d'));
                });
            }])->limit(5)->get();
    }
}
