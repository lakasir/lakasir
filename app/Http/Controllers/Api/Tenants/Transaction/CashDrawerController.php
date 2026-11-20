<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Tenants\CashDrawer;
use App\Services\Tenants\CashDrawerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashDrawerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'cash' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if (CashDrawer::lastOpened()->exists()) {
            return $this->buildResponse()
                ->setMessage('cash drawer is already open, close it before opening a new one')
                ->setCode(409)
                ->present();
        }

        CashDrawer::create([
            'cash' => $request->cash,
            'opened_by' => auth()->id(),
        ]);

        return $this->buildResponse()
            ->setMessage('success store money to cash drawer for today')
            ->present();
    }

    public function close(Request $request): JsonResponse
    {
        $request->validate([
            'counted_cash' => ['nullable', 'numeric', 'min:0'],
        ]);

        $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
        if (! $lastOpenedCashDrawer) {
            return $this->buildResponse()
                ->setMessage('cash drawer already closed or not opened yet')
                ->setCode(422)
                ->present();
        }

        $lastOpenedCashDrawer->update([
            'closed_by' => auth()->id(),
            'closed_at' => now(),
            'counted_cash' => $request->input('counted_cash'),
        ]);

        return $this->buildResponse()
            ->setMessage('success close cash drawer for today')
            ->present();
    }

    public function show(): JsonResponse
    {
        $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
        if (! $lastOpenedCashDrawer) {
            return $this->buildResponse()
                ->setMessage('cash drawer already closed or not opened yet')
                ->setCode(404)
                ->present();
        }

        return $this->buildResponse()
            ->setData($lastOpenedCashDrawer)
            ->present();
    }

    /**
     * Shift summary for the open cash drawer, or for the one given by ?id=.
     */
    public function summary(Request $request, CashDrawerService $cashDrawerService): JsonResponse
    {
        $request->validate([
            'id' => ['nullable', 'integer'],
        ]);

        $cashDrawer = $request->filled('id')
            ? CashDrawer::find($request->integer('id'))
            : CashDrawer::lastOpened()->first();

        if (! $cashDrawer) {
            return $this->buildResponse()
                ->setMessage('cash drawer not found or not opened yet')
                ->setCode(404)
                ->present();
        }

        return $this->buildResponse()
            ->setData($cashDrawerService->summary($cashDrawer))
            ->setMessage('success get cash drawer summary')
            ->present();
    }
}
