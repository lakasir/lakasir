<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Selling as SellingModel;
use App\Traits\HasParent;

class Selling extends RepositoryAbstract
{
    use HasParent;

    protected string $model = SellingModel::class;
}
