<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Price as PriceModel;
use App\Traits\HasParent;

class Price extends RepositoryAbstract
{
    use HasParent;

    protected string $model = PriceModel::class;

}
