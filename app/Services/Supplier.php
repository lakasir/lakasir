<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class Supplier extends RepositoryAbstract
{
    protected string $model = 'App\Models\Supplier';

    /**
     * @param Request $request
     * @return object
     * @throws BadRequestException
     */
    public function create(Request $request)
    {
        $numberGenerator = ( new NumberGeneratorBuilder() )->model($this->model)->prefix('CUS')->build();
        $request->merge([
            'code' => $numberGenerator->create()
        ]);
        $supplier = new $this->model();
        $supplier->fill($request->all());
        $supplier->save();

        return $supplier;
    }

    /**
     * @param Request $request
     * @param mixed $supplier
     * @return mixed
     * @throws BadRequestException
     */
    public function update(Request $request, $supplier)
    {
        $numberGenerator = ( new NumberGeneratorBuilder() )->model($this->model)->prefix('CUS')->build();
        if (!$request->code) {
            $request->merge([
                'code' => $numberGenerator->create()
            ]);
        }
        $supplier->fill($request->all());
        $supplier->save();

        return $supplier;
    }
}
