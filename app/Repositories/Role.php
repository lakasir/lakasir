<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use Spatie\Permission\Models\Role as RoleModel;

class Role extends RepositoryAbstract
{
    protected string $model = RoleModel::class;
}
