<?php

namespace App\Scopes;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $companyId = Company::currentId();

        if ($companyId !== null) {
            $builder->where($model->getTable().'.company_id', $companyId);

            return;
        }

        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        $builder->whereRaw('1 = 0');
    }
}
