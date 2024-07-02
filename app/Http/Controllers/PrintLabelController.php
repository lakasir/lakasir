<?php

namespace App\Http\Controllers;

use App\Models\Tenants\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;

class PrintLabelController extends Controller
{
    public function __invoke(Request $request, Product $product)
    {
        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator
            ->getBarcode($product->barcode ?? $product->sku, $generator::TYPE_CODE_128, 1, 30);

        $pdf = Pdf::loadView('filament.tenant.resources.products.pages.pdf.label', [
            'barcode' => $barcode,
            'count' => $request->count,
            'product' => $product,
        ]);
        $pdf->output();
        $domPdf = $pdf->getDomPDF();
        $canvas = $domPdf->getCanvas();
        $canvas->page_text(515, 820, 'Halaman {PAGE_NUM} dari {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf->stream();
    }
}
