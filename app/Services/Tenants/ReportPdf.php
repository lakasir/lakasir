<?php

namespace App\Services\Tenants;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

/**
 * Renders a report service's data as a PDF.
 *
 * The four report controllers differed only by their service and their Blade
 * view, so the layout, the page numbering and the download behaviour live here
 * once.
 */
class ReportPdf
{
    /**
     * @param  array<string, mixed>  $data  the report's data: reports, footer, header
     */
    public function render(string $view, array $data, bool $download = false): Response
    {
        $pdf = Pdf::loadView($view, [
            'reports' => $data['reports'],
            'footer' => $data['footer'],
            'header' => $data['header'],
        ])->setPaper('a4', 'landscape');

        // DomPDF builds the pages on first output; the numbering is drawn on
        // the canvas once they exist.
        $pdf->output();

        $pdf->getDomPDF()->getCanvas()
            ->page_text(720, 570, __('Page {PAGE_NUM} of {PAGE_COUNT}'), null, 10, [0, 0, 0]);

        return $download ? $pdf->download() : $pdf->stream();
    }
}
