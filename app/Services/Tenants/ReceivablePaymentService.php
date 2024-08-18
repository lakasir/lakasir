<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivablePayment;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class ReceivablePaymentService
{
    public function create(Receivable $receivable, array $data): ?Receivable
    {
        try {
            DB::beginTransaction();
            $data = array_merge($data, [
                'user_id' => Filament::auth()->id(),
                'last_receivable' => $receivable->rest_receivable,
                'receivable_id' => $receivable->getKey(),
            ]);
            ReceivablePayment::query()->create($data);
            $receivable->last_billing_date = $data['date'];
            $receivable->rest_receivable = $receivable->rest_receivable - $data['amount'];
            $payedreceivable = $receivable->fresh()->receivablePayments->sum('amount');
            if ($payedreceivable == $receivable->total_receivable) {
                $receivable->status = true;
            }
            $receivable->save();
            DB::commit();

            return $receivable;
        } catch (Exception) {
            DB::rollBack();
        }

        return null;
    }

    public function update(ReceivablePayment $receivablePayment, array $data)
    {
        try {
            DB::beginTransaction();
            $last_receivable = $receivablePayment->receivable->rest_receivable + $receivablePayment->amount;
            $data = array_merge($data, [
                'user_id' => Filament::auth()->id(),
                'last_receivable' => $last_receivable,
            ]);
            $receivablePayment->update($data);
            $receivablePayment->receivable->rest_receivable = $last_receivable - $data['amount'];
            $payedreceivable = $receivablePayment->receivable->fresh()->receivablePayments->sum('amount');
            if ($payedreceivable == $receivablePayment->receivable->total_receivable) {
                $receivablePayment->receivable->status = true;
            } else {
                $receivablePayment->receivable->status = false;
            }
            $receivablePayment->receivable->save();
            DB::commit();

            return $receivablePayment->receivable;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return null;
    }

    public function destroy(ReceivablePayment $receivablePayment): void
    {
        $receivable = $receivablePayment->receivable;
        $receivable->rest_receivable = $receivable->rest_receivable + $receivablePayment->amount;
        $receivable->last_billing_date = $receivable->receivablePayments()->latest('date')->first()->date;
        $receivable->status = false;
        $receivable->save();
        $receivablePayment->delete();
    }
}
