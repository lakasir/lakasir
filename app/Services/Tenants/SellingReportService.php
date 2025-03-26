<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class SellingReportService
{
    public function generate(array $data)
    {
        $timezone = Profile::get()->timezone;
        $about = About::first();
        $startDate = Carbon::parse($data['start_date'], $timezone)->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'], $timezone)->addDay()->setTimezone('UTC');

        $sellings = Selling::query()
            ->select()
            ->with(
                'sellingDetails:id,selling_id,product_id,qty,price,cost,discount_price',
                'sellingDetails.product:id,name,initial_price,selling_price,sku',
                'user:id,name,email'
            )
            ->when($data['start_date'] && $data['end_date'], function (Builder $query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $header = [
            'shop_name' => $about?->shop_name,
            'shop_location' => $about?->shop_location,
            'business_type' => $about?->business_type,
            'owner_name' => $about?->owner_name,
            'start_date' => $startDate->setTimezone($timezone)->format('d F Y'),
            'end_date' => $endDate->subDay()->setTimezone($timezone)->format('d F Y'),
        ];
        $reports = [];

        $totalQty = 0;
        $totalCost = 0;
        $totalGross = 0;
        $totalNet = 0;
        $totalGrossProfit = 0;
        $totalDiscount = 0;
        $totalDiscountPerItem = 0;
        $totalNetProfitBeforeDiscountSelling = 0;
        $totalNetProfitAfterDiscountSelling = 0;

        /** @var Selling $selling */
        foreach ($sellings as $selling) {
            $totalDiscountPerItemTemp = 0;
            $totalBeforeDiscountPerSelling = 0;
            $totalAfterDiscountPerSelling = 0;
            $totalNetProfitPerSelling = 0;
            $totalGrossProfitPerSelling = 0;
            $totalCostPerSelling = 0;
            $totalQtyPerSelling = 0;

            /** @var SellingDetail $detail */
            foreach ($selling->sellingDetails as $detail) {
                $totalDiscountPerItemTemp += ($detail->discount_price ?? 0);
                $totalBeforeDiscountPerSelling += $detail->price;
                $totalAfterDiscountPerSelling += ($detail->price - ($detail->discount_price ?? 0));
                $totalNetProfitPerSelling += (($detail->price - $detail->cost) - ($detail->discount_price ?? 0));
                $totalGrossProfitPerSelling += ($detail->price - $detail->cost);
                $totalCostPerSelling += $detail->cost;
                $totalQtyPerSelling += $detail->qty;

                $reports[] = [
                    'code' => $selling->code,
                    'sku' => $detail->product->sku,
                    'name' => $detail->product->name,
                    'selling_price' => $this->formatCurrency($detail->price / $detail->qty),
                    'selling' => $this->formatCurrency($detail->price - ($detail->discount_price ?? 0)),
                    'discount_price' => $this->formatCurrency($detail->discount_price ?? 0),
                    'initial_price' => $this->formatCurrency($detail->cost / $detail->qty),
                    'qty' => $detail->qty,
                    'cost' => $detail->cost,
                    'total_after_discount' => $this->formatCurrency($detail->price - ($detail->discount_price ?? 0)),
                    'net_profit' => $this->formatCurrency(($detail->price - ($detail->discount_price ?? 0)) - $detail->cost),
                    'gross_profit' => $this->formatCurrency($detail->price - $detail->cost),
                ];
            }

            $totalCost += $totalCostPerSelling;
            $totalDiscount += ($selling->discount_price ?? 0);
            $totalGross += $totalBeforeDiscountPerSelling;
            $totalNet += $totalAfterDiscountPerSelling;
            $totalNetProfitBeforeDiscountSelling += $totalNetProfitPerSelling;
            $totalNetProfitAfterDiscountSelling += ($totalNetProfitPerSelling - ($selling->discount_price ?? 0));
            $totalGrossProfit += $totalGrossProfitPerSelling;
            $totalDiscountPerItem += $totalDiscountPerItemTemp;
            $totalQty += $totalQtyPerSelling;
        }

        $footer = [
            'total_cost' => $this->formatCurrency($totalCost),
            'total_gross' => $this->formatCurrency($totalGross),
            'total_net' => $this->formatCurrency($totalNet - $totalDiscount),
            'total_net_price_after_discount_per_item' => $this->formatCurrency($totalNet),
            'total_net_price_after_discount_selling' => $this->formatCurrency($totalNet - $totalDiscount),
            'total_discount' => $this->formatCurrency($totalDiscount),
            'total_discount_per_item' => $this->formatCurrency($totalDiscountPerItem),
            'total_gross_profit' => $this->formatCurrency($totalGross - $totalCost),
            'total_net_profit_before_discount_selling' => $this->formatCurrency($totalNet - $totalCost),
            'total_net_profit_after_discount_selling' => $this->formatCurrency($totalNet - $totalDiscount - $totalCost),
            'total_qty' => $totalQty,
        ];

        return [
            'reports' => $reports,
            'footer' => $footer,
            'header' => $header,
        ];
    }

    private function formatCurrency($value)
    {
        return Number::format($value);
    }
}
