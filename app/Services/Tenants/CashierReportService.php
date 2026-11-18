<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class CashierReportService
{
    public function generate(array $data)
    {
        $timezone = Profile::get()->timezone;
        $about = About::first();
        $tzName = Carbon::parse($data['start_date'])->getTimezone()->getName();
        $startDate = Carbon::parse($data['start_date'], $timezone)->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'], $timezone)->addDay()->setTimezone('UTC');
        $sellings = Selling::query()
            ->select('id', 'code', 'user_id', 'created_at', 'total_price', 'total_cost', 'discount_price')
            ->with(
                'sellingDetails:id,selling_id,product_id,qty,price,cost,discount_price',
                'sellingDetails.product:id,name,initial_price,selling_price',
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

        $totalCost = 0;
        $totalGross = 0;
        $totalNet = 0;
        $totalGrossProfit = 0;
        $totalDiscount = 0;
        $totalDiscountPerItem = 0;
        $totalNetProfitBeforeDiscountSelling = 0;
        $totalNetProfitAfterDiscountSelling = 0;

        foreach ($sellings as $selling) {
            $totalDiscountPerItemTemp = 0;
            $totalBeforeDiscountPerSelling = 0;
            $totalAfterDiscountPerSelling = 0;
            $totalNetProfitPerSelling = 0;
            $totalGrossProfitPerSelling = 0;
            $totalCostPerSelling = 0;

            $reports[] = [
                'id' => $selling->id,
                'created_at' => Carbon::parse($selling->created_at)->setTimezone($tzName)->format('d F Y h:i'),
                'number' => $selling->code,
                'user' => $selling->user?->name ?? $selling->user?->email,
                'transaction' => [
                    'items' => $selling->sellingDetails->map(function ($item) use (&$totalDiscountPerItemTemp, &$totalBeforeDiscountPerSelling, &$totalAfterDiscountPerSelling, &$totalNetProfitPerSelling, &$totalGrossProfitPerSelling, &$totalCostPerSelling) {
                        $totalDiscountPerItemTemp += ($item->discount_price ?? 0);
                        $totalBeforeDiscountPerSelling += $item->price;
                        $totalAfterDiscountPerSelling += ($item->price - ($item->discount_price ?? 0));
                        $totalNetProfitPerSelling += (($item->price - $item->cost) - ($item->discount_price ?? 0));
                        $totalGrossProfitPerSelling += ($item->price - $item->cost);
                        $totalCostPerSelling += $item->cost;

                        return [
                            'product' => $item->product?->name,
                            'quantity' => $item->qty,
                            'product_price' => $this->formatCurrency($item->price / $item->qty),
                            'product_cost' => $this->formatCurrency($item->cost / $item->qty),
                            'price' => $this->formatCurrency($item->price),
                            'cost' => $this->formatCurrency($item->cost),
                            'discount_price' => $this->formatCurrency($item->discount_price ?? 0),
                            'total_after_discount' => $this->formatCurrency($item->price - ($item->discount_price ?? 0)),
                            'net_profit' => $this->formatCurrency(($item->price - ($item->discount_price ?? 0)) - $item->cost),
                            'gross_profit' => $this->formatCurrency($item->price - $item->cost),
                        ];
                    }),
                ],
                'total' => [
                    'cost' => $this->formatCurrency($totalCostPerSelling),
                    'discount' => $this->formatCurrency($totalDiscountPerItemTemp),
                    'gross_selling' => $this->formatCurrency($totalBeforeDiscountPerSelling),
                    'net_selling' => $this->formatCurrency($totalAfterDiscountPerSelling),
                    'discount_selling' => $this->formatCurrency($selling->discount_price ?? 0),
                    'total_net_profit' => $this->formatCurrency($totalNetProfitPerSelling),
                    'total_gross_profit' => $this->formatCurrency($totalGrossProfitPerSelling),
                    'grand_total' => $this->formatCurrency($totalAfterDiscountPerSelling - ($selling->discount_price ?? 0)),
                ],
            ];

            $totalCost += $totalCostPerSelling;
            $totalDiscount += ($selling->discount_price ?? 0);
            $totalGross += $totalBeforeDiscountPerSelling;
            $totalNet += $totalAfterDiscountPerSelling;
            $totalGrossProfit += $totalGrossProfitPerSelling;
            $totalDiscountPerItem += $totalDiscountPerItemTemp;
            $totalNetProfitBeforeDiscountSelling += $totalNetProfitPerSelling;
            $totalNetProfitAfterDiscountSelling += ($totalNetProfitPerSelling - ($selling->discount_price ?? 0));
        }

        $footer = [
            'total_cost' => $this->formatCurrency($totalCost),
            'total_gross' => $this->formatCurrency($totalGross),
            'total_net' => $this->formatCurrency($totalNet - $totalDiscount),
            'total_discount' => $this->formatCurrency($totalDiscount),
            'total_discount_per_item' => $this->formatCurrency($totalDiscountPerItem),
            'total_gross_profit' => $this->formatCurrency($totalGross - $totalCost),
            'total_net_profit_before_discount_selling' => $this->formatCurrency($totalNet - $totalCost),
            'total_net_profit_after_discount_selling' => $this->formatCurrency($totalNet - $totalDiscount - $totalCost),
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
