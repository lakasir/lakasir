<?php

namespace App\Http\Controllers\Api\Tenants\Reports;

use App\Http\Controllers\Controller;
use App\Services\Tenants\CashierReportService;
use Illuminate\Http\Request;

class CashierReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $cashierReportService = new CashierReportService();

        return $cashierReportService->generate($request->all());
    }
}
