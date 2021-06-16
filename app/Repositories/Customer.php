<?php

namespace App\Repositories;

use Sheenazien8\Hascrudactions\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Models\Customer as ModelsCustomer;
use App\Models\CustomerPoint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sheenazien8\Hascrudactions\Abstracts\LaTable;

class Customer extends RepositoryAbstract
{
    /**
     * @var CustomerType
     */
    private $customerType;

    public function __construct()
    {
        parent::__construct(new ModelsCustomer);
        $this->customerType = new CustomerType();
    }

    public function datatable(Request $request): LaTable
    {
        $items = $this->query()->toBase()
            ->addSelect([
                'total_point' => CustomerPoint::select(DB::raw("IF(SUM(point), SUM(point), 0)"))
                    ->whereColumn('customer_id', 'customers.id')
            ])
            ->latest();

        return $this->getObjectModel()->table($items);
    }


    public function create(Request $request): ModelsCustomer
    {
        try {
            DB::beginTransaction();
            $customer = $this->getObjectModel();
            $numberGenerator = (new NumberGeneratorBuilder())->model($customer->getMorphClass())->prefix('CUS')->build();
            $request->merge([
                'code' => $numberGenerator->create()
            ]);
            $customer->fill($request->all());
            $customer->save();
            $points = new CustomerPoint([
                'date' => today()->format('Y-m-d'),
                'point' => $request->point ?? 0
            ]);
            $customer->points()->save($points);
            DB::commit();

            return $customer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $customer): ModelsCustomer
    {
        try {
            DB::beginTransaction();
            $numberGenerator = (new NumberGeneratorBuilder())->model($this->model)->prefix('CUS')->build();
            if (!$request->code) {
                $request->merge([
                    'code' => $numberGenerator->create()
                ]);
            }
            $customer->fill($request->all());
            if ($request->customer_type_id) {
                $customer->customerType()->associate($this->customerType->find($request->customer_type_id));
            }
            $customer->save();
            DB::commit();

            return $customer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
