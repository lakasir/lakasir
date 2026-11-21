<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\SellingDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ProductReportService
{
    public function generate(array $data)
    {
        $timezone = $this->timezone();
        $about = About::first();
        // A missing date (the API allows null) means today, not a 500.
        $startDate = Carbon::parse($data['start_date'] ?? now()->toDateString(), $timezone)->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'] ?? now()->toDateString(), $timezone)->addDay()->setTimezone('UTC');

        // One grouped query for every product sold in the window, instead of
        // loading each product's details and aggregating them in PHP.
        $rows = SellingDetail::query()
            ->selectRaw('selling_details.product_id')
            ->selectRaw('SUM(selling_details.qty) as qty')
            ->selectRaw('SUM(selling_details.price) as price')
            ->selectRaw('SUM(selling_details.cost) as cost')
            ->selectRaw('SUM(selling_details.discount_price) as discount_price')
            ->selectRaw('MIN(sellings.code) as code')
            ->selectRaw('SUM(sellings.discount_price) as selling_discount_price')
            ->join('sellings', 'sellings.id', '=', 'selling_details.selling_id')
            ->join('products', 'products.id', '=', 'selling_details.product_id')
            ->whereBetween('sellings.date', [$startDate, $endDate])
            ->groupBy('selling_details.product_id', 'products.sku', 'products.name')
            ->orderBy('products.name')
            ->get();

        $header = [
            'shop_name' => $about?->shop_name,
            'shop_location' => $about?->shop_location,
            'business_type' => $about?->business_type,
            'owner_name' => $about?->owner_name,
            'start_date' => $startDate->setTimezone($timezone)->format('d F Y'),
            'end_date' => $endDate->setTimezone($timezone)->subDay()->format('d F Y'),
        ];

        $reports = [];
        $totalQty = 0;
        $totalCost = 0;
        $totalGross = 0;

        foreach ($rows as $row) {
            $qty = (float) $row->qty;
            $price = (float) $row->price;
            $cost = (float) $row->cost;
            $discountPerItem = (float) $row->discount_price;
            $sellingDiscount = (float) $row->selling_discount_price;

            $reports[] = [
                'code' => $row->code,
                'sku' => $row->sku,
                'name' => $row->name,
                'selling_price' => $this->formatCurrency($qty > 0 ? $price / $qty : $price),
                'selling' => $this->formatCurrency($price - $discountPerItem),
                'discount_price' => $this->formatCurrency($discountPerItem),
                'initial_price' => $this->formatCurrency($qty > 0 ? $cost / $qty : $cost),
                'qty' => $qty,
                'cost' => $this->formatCurrency($cost),
                'total_after_discount' => $this->formatCurrency($price - $discountPerItem - $sellingDiscount),
                'net_profit' => $this->formatCurrency($price - $discountPerItem - $cost),
                'gross_profit' => $this->formatCurrency($price - $cost),
            ];

            $totalQty += $qty;
            $totalCost += $cost;
            $totalGross += $price;
        }

        // The footer keeps the numbers the report page has always shown; the
        // discount subtotals are summed from the same grouped rows.
        $totalDiscountPerItem = (float) $rows->sum('discount_price');
        $totalSellingDiscount = (float) $rows->sum('selling_discount_price');
        $totalNet = $totalGross - $totalDiscountPerItem;

        $footer = [
            'total_cost' => $this->formatCurrency($totalCost),
            'total_gross' => $this->formatCurrency($totalGross),
            'total_net' => $this->formatCurrency($totalNet - $totalSellingDiscount),
            'total_discount' => $this->formatCurrency($totalSellingDiscount),
            'total_discount_per_item' => $this->formatCurrency($totalDiscountPerItem),
            'total_all_discount_per_item' => $this->formatCurrency($totalDiscountPerItem),
            'total_gross_profit' => $this->formatCurrency($totalGross - $totalCost),
            'total_net_profit_before_discount_selling' => $this->formatCurrency($totalNet - $totalCost),
            'total_net_profit_after_discount_selling' => $this->formatCurrency($totalNet - $totalSellingDiscount - $totalCost),
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

    /**
     * The cashier's time zone, falling back to the app's when the profile has
     * none (a fresh tenant, or a request without a signed-in user).
     */
    private function timezone(): string
    {
        return Profile::get()->timezone ?? config('app.timezone');
    }
}
