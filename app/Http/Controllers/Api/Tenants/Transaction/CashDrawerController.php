<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Tenants\CashDrawer;
use Illuminate\Http\Request;

class CashDrawerController extends Controller
{
    public function store(Request $request)
    {
        $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
        $request->validate([
            'cash' => [
                'required',
                'numeric',
                'min:0'
            ]
        ]);

        if ($lastOpenedCashDrawer) {
            $lastOpenedCashDrawer->update([
                'cash' => $request->cash
            ]);
        } else {
            CashDrawer::create([
                'cash' => $request->cash,
                'opened_by' => auth()->id()
            ]);
        }

        return $this->buildResponse()
            ->setMessage('success store money to cash drawer for today')
            ->present();
    }

    public function close()
    {
        $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
        if (!$lastOpenedCashDrawer) {
            return $this->buildResponse()
                ->setMessage('cash drawer already closed or not opened yet')
                ->setCode(422)
                ->present();
        }

        $lastOpenedCashDrawer->update([
            'closed_by' => auth()->id()
        ]);

        return $this->buildResponse()
            ->setMessage('success close cash drawer for today')
            ->present();
    }

    public function show()
    {
        $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
        if (!$lastOpenedCashDrawer) {
            return $this->buildResponse()
                ->setMessage('cash drawer already closed or not opened yet')
                ->setCode(404)
                ->present();
        }

        return $this->buildResponse()
            ->setData($lastOpenedCashDrawer)
            ->present();
    }
}
