<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;

class Category extends RepositoryAbstract
{
    protected string $model = 'App\Models\Category';
}
