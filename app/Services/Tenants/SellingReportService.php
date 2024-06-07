<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class SellingReportService
{
    public function generate(array $data)
    {
        $about = About::first();
        $tzName = Carbon::parse($data['start_date'])->getTimezone()->getName();
        $startDate = Carbon::parse($data['start_date'])->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'])->setTimezone('UTC');
        $sellings = Selling::query()
            ->select()
            ->with(
                'sellingDetails:id,selling_id,product_id,qty,price,cost,discount_price',
                'sellingDetails.product:id,name,initial_price,selling_price,sku',
                'user:id,name,email'
            )
            ->when($data['start_date'] ?? '', function (Builder $query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($data['end_date'] ?? '', function (Builder $query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $header = [
            'shop_name' => $about?->shop_name,
            'shop_location' => $about?->shop_location,
            'business_type' => $about?->business_type,
            'owner_name' => $about?->owner_name,
            'start_date' => $startDate->format('d F Y h:i'),
            'end_date' => $endDate->format('d F Y h:i'),
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
            $totalDiscountPerItem = 0;
            $totalBeforeDiscountPerSelling = 0;
            $totalAfterDiscountPerSelling = 0;
            $totalNetProfitPerSelling = 0;
            $totalGrossProfitPerSelling = 0;
            $totalCostPerSelling = 0;
            $totalQtyPerSelling = 0;

            /** @var SellingDetail $detail */
            foreach ($selling->sellingDetails as $detail) {
                $totalDiscountPerItem += $detail->discount_price;
                $totalBeforeDiscountPerSelling += $detail->price;
                $totalAfterDiscountPerSelling += ($detail->price - $detail->discount_price);
                $totalNetProfitPerSelling += (($detail->price - $detail->cost) - $detail->discount_price);
                $totalGrossProfitPerSelling += ($detail->price - $detail->cost);
                $totalCost += ($detail->cost / $detail->qty);
                $totalQtyPerSelling += $detail->qty;

                $reports[] = [
                    'code' => $selling->code,
                    'sku' => $detail->product->sku,
                    'name' => $detail->product->name,
                    'selling_price' => $this->formatCurrency($detail->price / $detail->qty),
                    'selling' => $this->formatCurrency($detail->price - $detail->discount_price),
                    'discount_price' => $this->formatCurrency($detail->discount_price ?? 0),
                    'initial_price' => $this->formatCurrency($detail->cost / $detail->qty),
                    'qty' => $detail->qty,
                    'cost' => $detail->cost,
                    'total_after_discount' => $this->formatCurrency($detail->price - $detail->discount_price),
                    'net_profit' => $this->formatCurrency(($detail->price - $detail->discount_price) - $detail->cost),
                    'gross_profit' => $this->formatCurrency($detail->price - $detail->cost),
                ];
            }

            $totalCost += $totalCostPerSelling;
            $totalDiscount += $selling->discount_price;
            $totalGross += $totalBeforeDiscountPerSelling;
            $totalNet += $totalAfterDiscountPerSelling;
            $totalNetProfitBeforeDiscountSelling += $totalNetProfitPerSelling;
            $totalNetProfitAfterDiscountSelling += ($totalNetProfitPerSelling - $selling->discount_price);
            $totalGrossProfit += $totalGrossProfitPerSelling;
            $totalDiscountPerItem += $totalDiscountPerItem;
            $totalQty += $totalQtyPerSelling;
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
            'total_qty' => $totalQty,
        ];

        $pdf = Pdf::loadView('reports.selling', compact('reports', 'footer', 'header'))
            ->setPaper('a4', 'landscape');
        $pdf->output();
        $domPdf = $pdf->getDomPDF();
        $canvas = $domPdf->getCanvas();
        $canvas->page_text(720, 570, 'Halaman {PAGE_NUM} dari {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf;
    }

    private function formatCurrency($value)
    {
        return Number::currency($value, Setting::get('currency', 'IDR'));
    }
}
