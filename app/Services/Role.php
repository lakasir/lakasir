<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Role as RoleModel;

class Role extends RepositoryAbstract
{
    protected string $model = RoleModel::class;
}
