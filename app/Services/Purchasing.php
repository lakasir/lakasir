<?php

namespace App\Services;

use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchasing as ModelsPurchasing;
use App\Models\PurchasingDetail;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Purchasing
{
    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function create(Request $request): void
    {
        $purchasing = $this->createPurchasing($request);
        $this->createPurchasingDetail($purchasing, $request);
    }

    /**
     * @param Request $request
     * @return \App\Models\Purchasing
     * @throws Exception
     */
    private function createPurchasing(Request $request)
    {
        $supplier = null;
        if (!$request->isNotFilled('supplier')) {
            $supplier = Supplier::find($request->supplier);
            if (!$supplier) {
                throw new Exception("Supplier is not found!", JsonResponse::HTTP_NOT_FOUND);
            }
        }
        $paymentMethod = PaymentMethod::find($request->payment_method);
        if (!$paymentMethod) {
            throw new Exception("Payment method is not found!", JsonResponse::HTTP_NOT_FOUND);
        }
        $total_initial_price = array_sum(Arr::pluck($request->items, 'initial_price'));
        $total_selling_price = array_sum(Arr::pluck($request->items, 'selling_price'));
        $total_qty = array_sum(Arr::pluck($request->items, 'qty'));
        $purchasing = new ModelsPurchasing();
        $purchasing->fill([
            'date' => $request->date ?? now()->format('Y-m-d'),
            'invoice_number' => $request->invoice_number ?? $this->generateInvoiceNumber(),
            'note' => $request->note,
            'is_paid' => $request->is_paid,
            'total_selling_price' => $total_selling_price,
            'total_initial_price' => $total_initial_price,
            'total_qty' => $total_qty,
        ]);
        $purchasing->paymentMethod()->associate($paymentMethod);
        if ($supplier) {
            $purchasing->supplier()->associate($supplier);
        }
        $purchasing->save();

        return $purchasing;
    }

    private function createPurchasingDetail(ModelsPurchasing $purchasing, Request $request): array
    {
        $purchasingDetails = [];
        foreach ($request->items as $item) {
            $itemRecord = Item::find($item['item_id']);
            if (!$itemRecord) {
                throw new Exception("Item is not found");
            }
            $purchasingDetail = new PurchasingDetail();
            $purchasingDetail->fill($item);
            $purchasingDetail->purchasing()->associate($purchasing);
            $purchasingDetail->item()->associate($itemRecord);
            $purchasingDetail->save();
            $purchasingDetails[] = $purchasingDetail;
        }

        return $purchasingDetails;
    }

    /** @return string  */
    private function generateInvoiceNumber(): string
    {
        $purchasing = ModelsPurchasing::latest()->first();
        return "INV".now()->format('Ymd'). str_pad($purchasing ? $purchasing->id : 1, 4, 0, STR_PAD_LEFT);
    }
}
