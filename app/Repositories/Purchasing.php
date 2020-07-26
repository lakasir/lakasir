<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Traits\HasParent;
use Illuminate\Http\Request;

class Purchasing extends RepositoryAbstract
{
    use HasParent;

    protected string $model = 'App\Models\Purchasing';
}
