<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Models\CustomerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Customer extends RepositoryAbstract
{
    protected string $model = 'App\Models\Customer';

    public function datatable(Request $request)
    {
        $items = $this->model::toBase()
                      ->addSelect([
                          'total_point' => CustomerPoint::select(DB::raw("IF(SUM(point), SUM(point), 0)"))
                              ->whereColumn('customer_id', 'customers.id')
                      ])
                      ->latest()
                      ->get();

        return $this->getObjectModel()->table($items);
    }


    public function create(Request $request)
    {
        $numberGenerator = ( new NumberGeneratorBuilder() )->model($this->model)->prefix('CUS')->build();
        $request->merge([
            'code' => $numberGenerator->create()
        ]);
        $customer = new $this->model();
        $customer->fill($request->all());
        $customer->save();
        $points = new CustomerPoint([
            'date' => today()->format('Y-m-d'),
            'point' => $request->point
        ]);
        $customer->points()->save($points);

        return $customer;
    }

    public function update(Request $request, $customer)
    {
        $numberGenerator = ( new NumberGeneratorBuilder() )->model($this->model)->prefix('CUS')->build();
        if (!$request->code) {
            $request->merge([
                'code' => $numberGenerator->create()
            ]);
        }
        $customer->fill($request->all());
        $customer->save();

        return $customer;
    }
}
