<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Traits\HasParent;

class CashDrawer extends RepositoryAbstract
{
    use HasParent;

    protected string $model = 'App\Models\CashDrawer';
}
