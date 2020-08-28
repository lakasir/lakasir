<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\PaymentMethod as PaymentMethodModel;
use Illuminate\Http\Request;

class PaymentMethod extends RepositoryAbstract
{
    protected string $model = PaymentMethodModel::class;

    public function create(Request $request)
    {
        $paymentMethd = $this->getObjectModel();
        $request->merge([
            'visible_in' => json_encode($request->visible_in),
        ]);
        $paymentMethd->fill($request->all());
        $paymentMethd->save();

        return $paymentMethd;
    }

    public function update(Request $request, $paymentMethd)
    {
        $request->merge(['visible_in' => json_encode($request->visible_in)]);
        $paymentMethd->fill($request->all());
        $paymentMethd->save();

        return $paymentMethd;
    }
}
