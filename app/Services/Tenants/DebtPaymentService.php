<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class DebtPaymentService
{
    public function create(Debt $debt, array $data): ?Debt
    {
        try {
            DB::beginTransaction();
            $data = array_merge($data, [
                'user_id' => Filament::auth()->id(),
                'last_debt' => $debt->rest_debt,
                'debt_id' => $debt->getKey(),
            ]);
            DebtPayment::query()->create($data);
            $debt->last_billing_date = $data['date'];
            $debt->rest_debt = $debt->rest_debt - $data['amount'];
            $payedDebt = $debt->fresh()->debtPayments->sum('amount');
            if ($payedDebt == $debt->total_debt) {
                $debt->status = true;
            }
            $debt->save();
            DB::commit();

            return $debt;
        } catch (Exception) {
            DB::rollBack();
        }

        return null;
    }

    public function update(DebtPayment $debtPayment, array $data): ?Debt
    {
        try {
            DB::beginTransaction();
            $last_debt = $debtPayment->debt->rest_debt + $debtPayment->amount;
            $data = array_merge($data, [
                'user_id' => Filament::auth()->id(),
                'last_debt' => $last_debt,
            ]);
            $debtPayment->update($data);
            $debtPayment->debt->rest_debt = $last_debt - $data['amount'];
            $payedDebt = $debtPayment->debt->fresh()->debtPayments->sum('amount');
            if ($payedDebt == $debtPayment->debt->total_debt) {
                $debtPayment->debt->status = true;
            } else {
                $debtPayment->debt->status = false;
            }
            $debtPayment->debt->save();
            DB::commit();

            return $debtPayment->debt;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(DebtPayment $debtPayment): void
    {
        $debt = $debtPayment->debt;
        $debt->rest_debt = $debt->rest_debt + $debtPayment->amount;
        $debt->last_billing_date = $debt->debtPayments()->latest('date')->first()->date;
        $debt->status = false;
        $debt->save();
        $debtPayment->delete();
    }
}
