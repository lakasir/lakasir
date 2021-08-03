<?php

namespace App\Services;

use App\Builder\NumberGeneratorBuilder;
use App\Models\Customer as CustomerModel;
use App\Models\CustomerPoint;
use App\Models\CustomerType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * @var CustomerModel
     */
    private $customer;
    /**
     * @var CustomerType
     */
    private $customerType;
    /**
     * Datatables raw columns
     *
     * @var rawColumns
     */
    protected $rawColumns = [];

    /**
     * Datatables default raw columns
     *
     * @var defaultRawColumns
     */
    protected $defaultRawColumns = [
        'created_at', 'action'
    ];

    public function __construct()
    {
        $this->customerType = new CustomerType();
        $this->customer = new CustomerModel();
    }

    /**
     * Datatable
     *
     * @param Request $request
     *
     * @return Response
     */
    public function datatable(Request $request): Response
    {
        $items = CustomerModel::latest()->get();

        return datatables($items)
            ->addColumn('checkbox', function ($model) {
                return view('datatables.checkbox', compact('model'));
            })
            ->addColumn('created_at', function ($model) {
                $date = (new Carbon($model->created_at))->diffForHumans();
                return view('datatables.date')->with('date', $date);
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->addColumn('action', function ($model) {
                $resources = except_last_word(request()->route()->getName());

                return view('datatables.action', [
                    'delete' => route("{$resources}.destroy", $model->id),
                    'model' => $model,
                    'resources' => $resources
                ]);
            })
            ->rawColumns(array_merge((array) $this->defaultRawColumns, $this->rawColumns))
            ->toJson();
    }

    /**
     * Create customer data
     *
     * @param Request $request
     *
     * @return CustomerModel
     */
    public function create(Request $request): CustomerModel
    {
        try {
            DB::beginTransaction();
            $customer = $this->customer;
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

    /**
     * Update customer data
     *
     * @param Request $request
     * @param CustomerModel $customer
     *
     * @return CustomerModel
     */
    public function update(Request $request, CustomerModel $customer): CustomerModel
    {
        try {
            DB::beginTransaction();
            $numberGenerator = (new NumberGeneratorBuilder())->model($customer->getMorphClass())->prefix('CUS')->build();
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

    /**
     * Bulk Destroy
     *
     * @param Request $request
     * @param string $column (optional)
     *
     * @return void
     */
    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $self = $this;
        if ($self->customer->query()->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->customer->query()->whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
