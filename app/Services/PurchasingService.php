<?php

namespace App\Services;

use App\Builder\NumberGeneratorBuilder;
use App\Repositories\Item;
use App\Repositories\Purchasing;
use App\Repositories\PurchasingDetail;
use App\Repositories\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service For Complect Logic which related with Purchasing
 */
class PurchasingService
{
    public function create(Request $request)
    {
        try {
            return DB::transaction(static function () use ($request) {
                $purchasingRepository = new Purchasing();
                $purchasingDetailRepository = new PurchasingDetail();
                $supplier = ( new Supplier() )->find($request->supplier_id);

                $totalIntialPrice = 0;
                $totalSellingPrice = 0;
                $totalQty = 0;
                array_map(function ($item) use (
                    &$totalIntialPrice,
                    &$totalSellingPrice,
                    &$totalQty
                ) {
                    $totalIntialPrice += $item['initial_price'];
                    $totalSellingPrice += $item['selling_price'];
                    $totalQty += $item['qty'];
                }, $request->items);
                $numberGenerator = ( new NumberGeneratorBuilder() )
                    ->model($purchasingRepository->getModel())
                    ->prefix('INV')
                    ->build();
                $invoiceNumber = $numberGenerator->create();
                $date = today()->format('Y-m-d');
                if ($request->date) {
                    $date = date('Y-m-d', strtotime($request->date));
                }
                $request->merge([
                    'total_initial_price' => $totalIntialPrice,
                    'total_selling_price' => $totalSellingPrice,
                    'total_qty' => $totalQty,
                    'date' => $date,
                    'invoice_number' => $invoiceNumber
                ]);
                $purchasing = $purchasingRepository->hasParent('supplier_id', $supplier)->create($request);

                foreach ($request->items as $itemData) {
                    $item = (new Item())->find($itemData['item_id']);
                    $intial_price = $item->prices->last()->initial_price;
                    $selling_price = $item->prices->last()->selling_price;
                    if ($intial_price != $itemData['initial_price'] || $selling_price != $itemData['selling_price']) {
                        /**
                         * TODO: create update price <sheenazien8 2020-07-25>
                         */
                    }
                    $request->merge($itemData);
                    $purchasingDetailRepository->hasParent('purchasing_id', $purchasing)
                                                ->hasParent('item_id', $item)
                                                ->create($request);
                    /**
                     * TODO: Update Stock <sheenazien8 2020-07-25>
                     */

                    /**
                     * TODO: create jurnal accounting <sheenazien8 2020-07-26>
                     *
                     */
                }

                return $purchasing;
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return abort(500, $e->getMessage());
        }
    }
}
