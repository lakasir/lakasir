<?php

namespace App\Services;

use App\Models\Customer as CustomerModel;
use App\Models\CustomerType as CustomerTypeModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CustomerType
{
    /**
     * @var CustomerTypeModel
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
        $this->customerType = new CustomerTypeModel();
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
        $items = CustomerTypeModel::latest()->get();

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
        if ($self->customerType->query()->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->customerType->query()->whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
