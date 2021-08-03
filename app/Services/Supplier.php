<?php

namespace App\Services;

use App\Models\Supplier as SupplierModel;
use App\Builder\NumberGeneratorBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class Supplier
{
    /**
     * @var SupplierModel
     */
    private $supplier;
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
        $this->supplier = new SupplierModel();
    }

    /**
     * Datatable
     *
     * @param Request $request
     * @return Response
     */
    public function datatable(Request $request): Response
    {
        $items = SupplierModel::latest()->get();

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
     * @param Request $request
     * @return SupplierModel
     * @throws BadRequestException
     */
    public function create(Request $request): SupplierModel
    {
        $supplier = new SupplierModel();
        $numberGenerator = (new NumberGeneratorBuilder())->model($supplier->getMorphClass())->prefix('SUP')->build();
        $request->merge([
            'code' => $numberGenerator->create()
        ]);
        $supplier->fill($request->all());
        $supplier->save();

        return $supplier;
    }

    /**
     * @param Request $request
     * @param SupplierModel $supplier
     * @return SupplierModel
     * @throws BadRequestException
     */
    public function update(Request $request, SupplierModel $supplier): SupplierModel
    {
        $numberGenerator = (new NumberGeneratorBuilder())->model($supplier->getMorphClass())->prefix('SUP')->build();
        if (!$request->code) {
            $request->merge([
                'code' => $numberGenerator->create()
            ]);
        }
        $supplier->fill($request->all());
        $supplier->save();

        return $supplier;
    }

    /**
     * Bulk Destroy
     *
     * @param Request $request
     * @param string $column (optional)
     * @return void
     */
    public function bulkDestroy(Request $request, string $column = 'id'): void
    {
        $self = $this;
        if ($self->supplier->query()->find($request->ids)->count() == 0) {
            abort(404);
        }
        DB::transaction(static function () use ($request, $self, $column) {
            collect($request->ids)
                ->chunk(1000)
                ->each(static function ($bulkChunk) use ($self, $column) {
                    $self->supplier->query()->whereIn($column, $bulkChunk)->delete();
                });
        });
    }
}
