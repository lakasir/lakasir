<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use Illuminate\Http\Request;

class Customer extends RepositoryAbstract
{
    protected string $model = 'App\Models\Customer';

    public function create(Request $request)
    {
        $numberGenerator = ( new NumberGeneratorBuilder() )->model($this->model)->prefix('CUS')->build();
        $request->merge([
            'code' => $numberGenerator->create()
        ]);
        $customer = new $this->model();
        $customer->fill($request->all());
        $customer->save();

        return $customer;
    }
}
