<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Helpers\NumberGenerator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Company extends RepositoryAbstract
{
    protected string $model = 'App\Models\Company';

    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            $user = User::first();
            if (!$request->reg_number) {
                $numberGenerator = (new NumberGeneratorBuilder())->prefix('LA')->model($self->model)->build();
                $request->merge([
                    'reg_number' => $numberGenerator->create()
                ]);
            }
            $company = new $self->model();
            $company->fill($request->all());
            $company->user()->associate($user);
            $company->save();

            return $company;
        });
    }
}
