<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenants\PrinterRequest;
use App\Models\Tenants\Printer;
use Illuminate\Http\JsonResponse;

class PrinterController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->buildResponse()
            ->setData(Printer::all())
            ->setMessage('Data retrieved successfully')
            ->present();
    }

    public function store(PrinterRequest $request): JsonResponse
    {
        Printer::create($request->validated());

        return $this->buildResponse()
            ->setMessage('Data saved successfully')
            ->present();
    }

    public function update(PrinterRequest $request, Printer $printer): JsonResponse
    {
        $printer->update($request->validated());

        return $this->buildResponse()
            ->setMessage('Data updated successfully')
            ->present();
    }

    public function destroy(Printer $printer): JsonResponse
    {
        $printer->delete();

        return $this->buildResponse()
            ->setMessage('Data deleted successfully')
            ->present();
    }
}
