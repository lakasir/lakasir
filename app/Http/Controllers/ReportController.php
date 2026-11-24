<?php

namespace App\Http\Controllers;

use App\Services\Tenants\CashierReportService;
use App\Services\Tenants\ProductReportService;
use App\Services\Tenants\PurchasingReportService;
use App\Services\Tenants\ReportPdf;
use App\Services\Tenants\SellingReportService;
use Illuminate\Http\Request;

/**
 * The report PDFs. One controller for all four reports: the route says which
 * one, and the service and view follow from it.
 */
class ReportController extends Controller
{
    /**
     * Service, Blade view and permission per report type.
     *
     * @var array<string, array{service: class-string, view: string, permission: string}>
     */
    private const REPORTS = [
        'selling' => ['service' => SellingReportService::class, 'view' => 'reports.selling', 'permission' => 'generate selling report'],
        'product' => ['service' => ProductReportService::class, 'view' => 'reports.product', 'permission' => 'generate product report'],
        'cashier' => ['service' => CashierReportService::class, 'view' => 'reports.cashier', 'permission' => 'generate cashier report'],
        'purchasing' => ['service' => PurchasingReportService::class, 'view' => 'reports.purchasing', 'permission' => 'generate purchasing report'],
    ];

    public function __invoke(Request $request, ReportPdf $pdf, string $type)
    {
        abort_unless(isset(self::REPORTS[$type]), 404);

        // One route serves four reports, so the permission depends on the type
        // rather than living on the route.
        abort_unless($request->user()?->can(self::REPORTS[$type]['permission']), 403);

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $report = self::REPORTS[$type];
        $data = app($report['service'])->generate($request->all());

        // The report page's own download button asks for a download rather than
        // an inline stream.
        return $pdf->render($report['view'], $data, $request->ajax());
    }
}
