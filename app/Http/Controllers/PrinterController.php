<?php

namespace App\Http\Controllers;

use App\Models\Tenants\Printer;
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
}
