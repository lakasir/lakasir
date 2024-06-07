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

        /** @var Selling $selling */
        foreach ($sellings as $selling) {
            /** @var SellingDetail $detail */
            foreach ($selling->sellingDetails as $detail) {
                $reports[] = [
                    'code' => $selling->code,
                    'sku' => $detail->product->sku,
                    'name' => $detail->product->name,
                    'selling_price' => $this->formatCurrency($detail->price / $detail->qty),
                    'selling' => $this->formatCurrency($detail->price - $detail->discount_price),
                    'discount_price' => $this->formatCurrency($detail->discount_price ?? 0),
                    'initial_price' => $this->formatCurrency($detail->cost),
                    'qty' => $detail->qty,
                ];
            }
        }

        $footer = [
            'total_gross_profit' => $this->formatCurrency($sellings->sum('total_price')),
            'total_cost' => $this->formatCurrency($sellings->sum('total_cost')),
            'total_net_profit' => $this->formatCurrency($sellings->sum('total_price') - $sellings->sum('total_cost')),
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
