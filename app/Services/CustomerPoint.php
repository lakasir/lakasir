<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Traits\HasParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPoint extends RepositoryAbstract
{
    use HasParent;

    protected string $model = 'App\Models\CustomerPoint';
}
