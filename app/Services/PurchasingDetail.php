<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Traits\HasParent;
use Illuminate\Http\Request;

class PurchasingDetail extends RepositoryAbstract
{
    use HasParent;

    protected string $model = 'App\Models\PurchasingDetail';
}
