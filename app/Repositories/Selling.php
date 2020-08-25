<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Selling as SellingModel;

class Selling extends RepositoryAbstract
{
    protected string $model = SellingModel::class;
}
