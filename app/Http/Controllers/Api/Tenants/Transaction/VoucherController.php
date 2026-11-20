<?php

namespace App\Http\Controllers\Api\Tenants\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\Vouchers\VoucherCheckRequest;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    /**
     * Check a voucher code against a cart total without using it up, so the
     * POS can show the discount before checkout.
     */
    public function check(VoucherCheckRequest $request, VoucherService $voucherService): JsonResponse
    {
        $totalPrice = (float) $request->validated('total_price');
        $applied = $voucherService->applyable($request->validated('code'), $totalPrice);
        $discount = min($applied->calculate(), $totalPrice);

        return $this->buildResponse()
            ->setData([
                'code' => $applied->voucher->code,
                'name' => $applied->voucher->name,
                'type' => $applied->voucher->type,
                'nominal' => $applied->voucher->nominal,
                'discount' => $discount,
                'total_after_discount' => $totalPrice - $discount,
            ])
            ->setMessage('voucher can be used')
            ->present();
    }
}
