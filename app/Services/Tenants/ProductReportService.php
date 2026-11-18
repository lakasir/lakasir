<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Product;
use App\Models\Tenants\Profile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ProductReportService
{
    public function generate(array $data)
    {
        $timezone = Profile::get()->timezone;
        $about = About::first();
        $tzName = Carbon::parse($data['start_date'])->getTimezone()->getName();
        $startDate = Carbon::parse($data['start_date'], $timezone)->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'], $timezone)->addDay()->setTimezone('UTC');

        $products = Product::query()
            ->with(
                ['sellingDetails' => function ($builder) use ($startDate, $endDate) {
                    $builder->whereHas('selling', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
                }],
            )
            ->whereHas('sellingDetails', function ($query) use ($startDate, $endDate) {
                $query->whereHas('selling', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
            })
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
        $totalAllDiscountPerItem = 0;
        $totalNetProfitBeforeDiscountSelling = 0;
        $totalNetProfitAfterDiscountSelling = 0;

        /** @var Product $product */
        foreach ($products as $product) {
            $sellingDetails = $product->sellingDetails;
            $totalAllDiscountPerItemTemp = 0;

            $totalCostPerSelling = $sellingDetails->sum('cost');
            $totalDiscountPerItem = $sellingDetails->sum('discount_price');
            $totalAllDiscountPerItemTemp += $sellingDetails->sum('discount_price');
            $totalBeforeDiscountPerSelling = $sellingDetails->sum('price');
            $totalAfterDiscountPerSelling = $totalBeforeDiscountPerSelling - $totalDiscountPerItem;
            $totalNetProfitPerSelling = (($totalBeforeDiscountPerSelling - $totalCostPerSelling) - $totalDiscountPerItem);
            $totalGrossProfitPerSelling = $totalBeforeDiscountPerSelling - $totalCostPerSelling;
            $totalQtyPerSelling = $sellingDetails->sum('qty');

            $reports[] = [
                'code' => $product->sellingDetails->first()->selling->code,
                'sku' => $product->sku,
                'name' => $product->name,
                'selling_price' => $this->formatCurrency($product->sellingDetails->sum('price') / $product->sellingDetails->sum('qty')),
                'selling' => $this->formatCurrency($totalBeforeDiscountPerSelling - $totalDiscountPerItem),
                'discount_price' => $this->formatCurrency($totalDiscountPerItem),
                'initial_price' => $this->formatCurrency($totalCostPerSelling / $totalQtyPerSelling),
                'qty' => $totalQtyPerSelling,
                'cost' => $totalCostPerSelling,
                'total_after_discount' => $this->formatCurrency($totalAfterDiscountPerSelling - $totalDiscountPerItem),
                'net_profit' => $this->formatCurrency($totalBeforeDiscountPerSelling - $totalDiscountPerItem - $totalCostPerSelling),
                'gross_profit' => $this->formatCurrency($totalBeforeDiscountPerSelling - $totalCostPerSelling),
            ];

            $totalCost += $totalCostPerSelling;
            $totalGross += $totalBeforeDiscountPerSelling;
            $totalNet += $totalAfterDiscountPerSelling;
            $totalNetProfitBeforeDiscountSelling += $totalNetProfitPerSelling;
            $totalGrossProfit += $totalGrossProfitPerSelling;
            $totalAllDiscountPerItem += $totalAllDiscountPerItemTemp;
            $totalQty += $totalQtyPerSelling;

            foreach ($sellingDetails as $sellingDetail) {
                $totalDiscount += ($sellingDetail->selling->discount_price ?? 0.0);
                $totalNetProfitAfterDiscountSelling += ($totalNetProfitPerSelling - ($sellingDetail->selling->discount_price ?? 0.0));
            }
        }

        $footer = [
            'total_cost' => $this->formatCurrency($totalCost),
            'total_gross' => $this->formatCurrency($totalGross),
            'total_net' => $this->formatCurrency($totalNet - $totalDiscount),
            'total_discount' => $this->formatCurrency($totalDiscount),
            'total_discount_per_item' => $this->formatCurrency($totalDiscountPerItem),
            'total_all_discount_per_item' => $this->formatCurrency($totalAllDiscountPerItem),
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
