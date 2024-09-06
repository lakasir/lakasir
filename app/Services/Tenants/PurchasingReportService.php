<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Purchasing;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class PurchasingReportService
{
    public function generate(?array $data)
    {
        $profile = Profile::get();
        $timezone = $profile->timezone;
        $about = About::first();
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $header = [
            'shop_name' => $about?->shop_name,
            'shop_location' => $about?->shop_location,
            'business_type' => $about?->business_type,
            'owner_name' => $about?->owner_name,
            'start_date' => $startDate->setTimezone($timezone)->format('d F Y'),
            'end_date' => $endDate->subDay()->setTimezone($timezone)->format('d F Y'),
        ];

        $reports = [];
        /** @var Collection<Purchasing> $purchasings */
        $purchasings = Purchasing::query()
            ->with(['stocks.product', 'supplier'])
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $grand_total_initial_price = 0;
        $grand_total_selling_price = 0;
        foreach ($purchasings as $key => $purchasing) {
            $subtotal_total_initial_price = 0;
            $subtotal_total_selling_price = 0;
            $reports[$key] = [
                'supplier' => $purchasing->supplier->name,
                'date' => $purchasing->date,
                'subtotal_total_initial_price' => 0,
                'subtotal_total_selling_price' => 0,
            ];

            foreach ($purchasing->stocks as $keyS => $stock) {
                $reports[$key]['stocks'][$keyS] = [
                    'product_name' => $stock->product->name,
                    'product_unit' => $stock->product->unit,
                    'init_stock' => $stock->init_stock,
                    'initial_price' => Number::format($stock->initial_price),
                    'total_initial_price' => Number::format($stock->total_initial_price),
                    'selling_price' => Number::format($stock->selling_price),
                    'total_selling_price' => Number::format($stock->total_selling_price),
                ];
                $subtotal_total_initial_price += $stock->total_initial_price;
                $subtotal_total_selling_price += $stock->total_selling_price;
            }
            $reports[$key]['subtotal_total_initial_price'] = Number::format($subtotal_total_initial_price);
            $reports[$key]['subtotal_total_selling_price'] = Number::format($subtotal_total_selling_price);
            $grand_total_initial_price += $subtotal_total_initial_price;
            $grand_total_selling_price += $subtotal_total_selling_price;
        }

        $footer = [
            'grand_total_initial_price' => Number::format($grand_total_initial_price),
            'grand_total_selling_price' => Number::format($grand_total_selling_price),
        ];

        return [
            'reports' => $reports,
            'footer' => $footer,
            'header' => $header,
        ];
    }
}
