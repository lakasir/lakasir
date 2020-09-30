<?php

namespace App\Services;

use App\Builder\NumberGeneratorBuilder;
use App\Models\Selling as SellingModel;
use App\Repositories\Customer;
use App\Repositories\CustomerPoint;
use App\Repositories\Item;
use App\Repositories\PaymentMethod;
use App\Repositories\Selling;
use App\Repositories\SellingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Lakasir\UserLoggingActivity\Facades\Activity;

/**
 * Service For Complect Logic which related with Selling
 */
class SellingService
{
    /**
     * @var App\Repositories\Item
     */
    private $item;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var Selling
     */
    private $selling;


    public function __construct()
    {
        $this->item = new Item();
        $this->customer = new Customer();
        $this->paymentMethod = new PaymentMethod();
        $this->selling = new Selling;
        $this->sellingDetail = new SellingDetail;
    }

    /**
     * create item selling
     *
     * @param App\Http\Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self): array
        {
            $customer = $self->customer->query()->find($request->customer_id);
            $paymentMethod = $self->paymentMethod->find($request->payment_method_id);
            $numberGeneratorBuilder = new NumberGeneratorBuilder();
            $numberTransaction = $numberGeneratorBuilder->model($self->selling->getModel())->prefix('SEL')->build();
            $totalSellingPrice = $self->item->totalPriceByRequest($request->items);
            $totalInitialPrice = $self->item->totalPriceByRequest($request->items, 'initial_price');
            $totalQty = 0;
            foreach ($request->items as $item) {
                $totalQty += $item['qty'];
            }
            $request->merge([
                'number_transaction' => $numberTransaction->create(),
                'transaction_date' => now()->format('Y-m-d'),
                'total_price' => $totalSellingPrice,
                'total_qty' => $totalQty,
                'total_profit' => $totalSellingPrice - $totalInitialPrice,
                'refund' => $request->money - $totalSellingPrice,
            ]);
            $selling = $self->selling->if($customer, function ($repository) use ($customer)
            {
                $points = (new CustomerPoint())->getObjectModel([
                    'date' => today()->format('Y-m-d'),
                    'point' => optional($customer->customerType)->default_point ?? 0
                ]);
                $customer->points()->save($points);

                return $repository->hasParent('customer_id', $customer);
            })->if($paymentMethod, function ($repository) use ($paymentMethod)
            {
                return $repository->hasParent('payment_method_id', $paymentMethod);
            })->hasParent('user_id', auth()->user())
              ->create($request);

            foreach ($request->items as $itemRequest) {
               $item = $self->item->find($itemRequest['id']);
               // get stock paling awal dibeli
               $lastStock = $item->last_stock;
               $lastStock->amount = $lastStock->amount - $itemRequest['qty'];
               $lastStock->save();

               $request->merge([
                   'price' => $item->last_price->selling_price,
                   'profit' => $item->last_price->selling_price - $item->last_price->initial_price,
                   'qty' => $itemRequest['qty'],
               ]);
               $sellingDetail = $self->sellingDetail->if($item, function ($repository) use ($item)
               {
                   return $repository->hasParent('item_id', $item);
               })->hasParent('selling_id', $selling)->create($request);
            }
            Activity::modelable($selling)->auth()->creating();

            return $selling->toArray();
        });
    }

    /**
     * get List Item for cashier list Item
     *
     * @param App\Http\Request $request
     * @return array
     */
    public function list_item(Request $request): array
    {
        $query = $this->item->query();
        $items = $query->select('name', 'id', 'unit_id')
                            ->with('media', 'prices', 'log_stocks', 'unit')
                            ->when($request->search, function ($query) use ($request) {
                                return $query->where('name', 'LIKE', $request->search.'%') ;
                            })
                            ->latest()
                            ->get()->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'image' => optional($item->media->first())->get_full_name ?? config('setting.image.empty'),
                                    'stock' => $item->stock,
                                    'unit_name' => optional($item->unit)->name,
                                    'selling_price' => optional($item->last_price)->selling_price,
                                    'selling_price_format' => price_format(optional($item->last_price)->selling_price)
                                ];
                            });

        return $items->toArray();
    }

    /**
     * map activity to get i want response
     *
     * @param App\Http\Request $request
     * @return array
     */
    public function activity(Request $request): array
    {
        $activities = $this->selling->activity($request);
        $previousDate;
        $transactionDate = [];
        $index = 0;
        $activities->each(function(SellingModel $activity, int $key)
            use($activities, &$previousDate, &$transactionDate, &$index)
            {
                if ($previousDate != $activity->transaction_date) {
                    $transactionDate[$index] = $activity->transaction_date;
                    $index++;
                }
                $previousDate = $activity->transaction_date;
            });
        $resultActivities = [];
        for ($i = 0; $i < count($transactionDate); $i++) {
            $activities->map(function(SellingModel $activity, int $key)
                use($transactionDate, &$i, &$resultActivities)
                {
                    if ($transactionDate[$i] == $activity->transaction_date) {
                        return $resultActivities[$transactionDate[$i]][$key] = $activity->toArray();
                    }
                });
        }

        return $resultActivities;
    }

    public function detail(SellingModel $selling): array
    {
        $sellingDetail = $selling->sellingDetail->map(fn($sD) => [
            'item_name' => $sD->item->name,
            'qty' => $sD->qty,
            'price' => $sD->price,
            'profit' => $sD->profit
        ])->toArray();

        return $sellingDetail;
    }
}
