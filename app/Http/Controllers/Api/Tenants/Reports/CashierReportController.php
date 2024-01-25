<?php

namespace App\Http\Controllers\Api\Tenants\Reports;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Selling;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashierReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        $sellings = Selling::query()
            ->select('id', 'code', 'user_id', 'created_at', 'total_price', 'total_cost')
            ->with(
                'sellingDetails:id,selling_id,product_id,qty,price,cost',
                'sellingDetails.product:id,name',
                'user:id,name,email'
            )
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $parsedToTimezone = Carbon::parse($request->start_date)->setTimezone('UTC');
                $query->where('created_at', '>=', $parsedToTimezone);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $parsedToTimezone = Carbon::parse($request->end_date)->setTimezone('UTC');
                $query->where('created_at', '<=', $parsedToTimezone);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $reports = [];
        foreach ($sellings as $selling) {
            $reports[] = [
                'transaction' => [
                    'id' => $selling->id,
                    'created_at' => $selling->created_at,
                    'number' => $selling->code,
                    'user' => $selling->user?->name ?? $selling->user?->email,
                    'items' => $selling->sellingDetails->map(function ($item) {
                        return [
                            'product' => $item->product?->name,
                            'quantity' => $item->qty,
                            'price' => $item->price,
                            'cost' => $item->cost,
                            'net_profit' => $item->price - $item->cost,
                        ];
                    }),
                ],
                'total' => [
                    'gross_profit' => $selling->total_price,
                    'total_cost' => $selling->total_cost,
                    'total_net_profit' => $selling->total_price - $selling->total_cost,
                ],
            ];
        }

        $pdf = Pdf::loadView('reports.cashier', compact('reports'));

        return $pdf->download('cashier-report.pdf');
    }
}
