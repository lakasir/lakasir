<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\SellingDetail as SellingDetailModel;

class SellingDetail extends RepositoryAbstract
{
    protected string $model = SellingDetailModel::class;
}
