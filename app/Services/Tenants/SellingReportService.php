<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Selling;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class SellingReportService
{
    public function generate(array $data)
    {
        $about = About::first();
        $tzName = Carbon::parse($data['start_date'])->getTimezone()->getName();
        $startDate = Carbon::parse($data['start_date'])->setTimezone('UTC');
        $endDate = Carbon::parse($data['end_date'])->setTimezone('UTC');
        $sellings = Selling::query()
            ->select('id', 'code', 'user_id', 'created_at', 'total_price', 'total_cost')
            ->with(
                'sellingDetails:id,selling_id,product_id,qty,price,cost',
                'sellingDetails.product:id,name,initial_price,selling_price',
                'user:id,name,email'
            )
            ->when($data['start_date'] ?? '', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->when($data['end_date'] ?? '', function ($query) use ($endDate) {
                $query->where('created_at', '<=', $endDate);
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
        $footer = [
            'total_gross_profit' => $this->formatCurrency($sellings->sum('total_price')),
            'total_cost' => $this->formatCurrency($sellings->sum('total_cost')),
            'total_net_profit' => $this->formatCurrency($sellings->sum('total_price') - $sellings->sum('total_cost')),
        ];

        $pdf = Pdf::loadView('reports.cashier', compact('reports', 'footer', 'header'))
            ->setPaper('a4', 'landscape');
        $pdf->output();
        $domPdf = $pdf->getDomPDF();
        $canvas = $domPdf->get_canvas();
        $canvas->page_text(720, 570, 'Halaman {PAGE_NUM} dari {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf;
    }

    private function formatCurrency($value)
    {
        return number_format($value, 0, ',', '.');
    }
}
