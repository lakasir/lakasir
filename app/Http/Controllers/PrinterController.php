<?php

namespace App\Http\Controllers;

use App\Models\Tenants\Printer;
use App\Models\Tenants\Selling;
use App\Services\Tenants\PrinterService;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    public function index()
    {
        return $this->buildResponse()
            ->setData(Printer::all())
            ->setMessage('Data retrieved successfully')
            ->present();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ip_address' => 'required',
            'port' => 'nullable',
            'driver' => 'required',
        ]);

        Printer::create($request->all());

        return $this->buildResponse()
            ->setMessage('Data saved successfully')
            ->present();
    }

    public function update(Request $request, Printer $printer)
    {
        $request->validate([
            'name' => 'required',
            'ip_address' => 'required',
            'port' => 'nullable',
            'driver' => 'required',
        ]);

        $printer->update($request->all());

        return $this->buildResponse()
            ->setMessage('Data updated successfully')
            ->present();
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();

        return $this->buildResponse()
            ->setMessage('Data deleted successfully')
            ->present();
    }

    public function print(Request $request, Selling $selling)
    {
        try {
            $selling->load([
                'sellingDetails.product',
                'user',
                'table',
                'member',
                'paymentMethod'
            ]);
            
            $printerService = new PrinterService();
            
            $result = $printerService
                ->buildReceiptFromSelling($selling)
                ->print();

            return $this->buildResponse()
                ->setData($result)
                ->setMessage('Print successful')
                ->present();
        } catch (\Exception $e) {
            return $this->buildResponse()
                ->setMessage($e->getMessage())
                ->setCode(500)
                ->present();
        }
    }

    public function printWeb(Request $request, Selling $selling)
    {
        try {
            $selling->load([
                'sellingDetails.product',
                'user',
                'table',
                'member',
                'paymentMethod'
            ]);
            
            $printerService = new PrinterService();
            
            $result = $printerService
                ->buildReceiptFromSelling($selling)
                ->print();

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Print successful'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
