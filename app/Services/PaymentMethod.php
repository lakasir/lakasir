<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\PaymentMethod as PaymentMethodModel;
use Illuminate\Http\Request;

class PaymentMethod extends RepositoryAbstract
{
    protected string $model = PaymentMethodModel::class;

    public function create(Request $request)
    {
        $paymentMethd = $this->getObjectModel();
        $result = [];
        if (isset($request->visible_in)) {
            foreach ($request->visible_in as $key => $value) {
                $result[$key] = true;
            }
        }
        $request->merge([
            'visible_in' => json_encode($result),
            'can_delete' => true
        ]);
        $paymentMethd->fill($request->all());
        $paymentMethd->save();

        return $paymentMethd;
    }

    public function update(Request $request, $paymentMethd)
    {
        $result = [];
        if (isset($request->visible_in)) {
            foreach ($request->visible_in as $key => $value) {
                $result[$key] = true;
            }
        }
        $request->merge([
            'visible_in' => json_encode($result),
            'can_delete' => true
        ]);
        $paymentMethd->fill($request->all());
        $paymentMethd->save();

        return $paymentMethd;
    }
}
