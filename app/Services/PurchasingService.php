<?php

namespace App\Services;

use App\Builder\NumberGeneratorBuilder;
use App\Repositories\Item;
use App\Repositories\PaymentMethod;
use App\Repositories\Price;
use App\Repositories\Purchasing;
use App\Repositories\PurchasingDetail;
use App\Repositories\Stock;
use App\Repositories\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service For Complect Logic which related with Purchasing
 */
class PurchasingService
{
    /**
     * @param
     */
    public function __construct()
    {
        $this->price = new Price();
    }

    public function create(Request $request)
    {
        try {
            $self = $this;
            return DB::transaction(static function () use ($request, $self) {
                $purchasingRepository = new Purchasing();
                $purchasingDetailRepository = new PurchasingDetail();
                $supplier = ( new Supplier() )->find($request->supplier_id);
                $paymentMethod = ( new PaymentMethod() )->find($request->payment_method);

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
                $purchasing = $purchasingRepository->hasParent('user_id', auth()->user())
                                                   ->hasParent('payment_method_id', $paymentMethod)
                                                   ->hasParent('supplier_id', $supplier)->create($request);

                foreach ($request->items as $itemData) {
                    $item = (new Item())->find($itemData['item_id']);
                    $intial_price = $item->prices->last()->initial_price;
                    $selling_price = $item->prices->last()->selling_price;
                    $price = $item->prices->last();
                    $newPrice = false;
                    if ($intial_price != $itemData['initial_price'] || $selling_price != $itemData['selling_price']) {
                        $priceData = array_merge($itemData, [
                            'date' => $date
                        ]);
                        $priceRequest = new Request($priceData);
                        $newPrice = $self->price->hasParent('item_id', $item)->create($priceRequest);
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
                    $item = ( new Item )->find($itemData['item_id']);
                    $request->merge([
                        'amount' =>$itemData['qty'],
                        'date' => $date
                    ]);
                    $stock = ( new Stock )->hasParent('itemd_id', $item)
                                          ->if($newPrice, function($repository) use ($newPrice)
                                          {
                                              return $repository->hasParent('price_id', $newPrice);
                                          })
                                          ->if(!$newPrice, function($repository) use ($price)
                                          {
                                              return $repository->hasParent('price_id', $price);
                                          })
                                          ->create($request);

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
