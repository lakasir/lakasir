<?php

namespace App\Services;

use App\Repositories\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Service For Complect Logic which related with Selling
 */
class SellingService
{
    public function __construct()
    {
        $this->item = new Item;
    }


    public function create(Request $request)
    {
    }
    /**
     * get List Item for cashier list Item
     *
     * @params App\Http\Request $request
     * @return array
     */
    public function list_item(Request $request): array
    {
        $items = $this->item->getModel()::select('name', 'id')->with('media', 'prices', 'log_stocks')
                            ->get()->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'image' => optional($item->media->first())->get_full_name ?? config('setting.image.empty'),
                                    'stock' => optional($item->last_stock)->current_stock ?? __('app.items.column.stock.empty'),
                                    'selling_price' => optional($item->last_price)->selling_price
                                ];
                            });

        return $items->toArray();
    }
}
