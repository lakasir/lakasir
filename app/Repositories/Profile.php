<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Traits\HasParent;

class Profile extends RepositoryAbstract
{
    use HasParent;

    protected string $model = 'App\Models\Profile';
}
