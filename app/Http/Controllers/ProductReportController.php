<?php

namespace App\Http\Controllers;

use App\Services\Tenants\ProductReportService;
use Illuminate\Http\Request;

class ProductReportController extends Controller
{
    public function __invoke(Request $request, ProductReportService $sellingReportService)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $generate = $sellingReportService->generate($request->all());
        if ($request->ajax()) {
            return $generate->download();
        }

        return $generate->stream();
    }
}
