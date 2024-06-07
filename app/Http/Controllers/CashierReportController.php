<?php

namespace App\Http\Controllers;

use App\Services\Tenants\CashierReportService;
use Illuminate\Http\Request;

class CashierReportController extends Controller
{
    public function __invoke(Request $request, CashierReportService $cashierReportService)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $generate = $cashierReportService->generate($request->all());
        if ($request->ajax()) {
            return $generate->download();
        }

        return $generate->stream();
    }
}
