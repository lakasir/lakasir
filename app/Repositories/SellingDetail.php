<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\SellingDetail as SellingDetailModel;
use App\Traits\HasParent;

class SellingDetail extends RepositoryAbstract
{
    use HasParent;
    protected string $model = SellingDetailModel::class;
}
