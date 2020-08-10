<?php

namespace App\Services;

use App\Repositories\Role;
use App\Repositories\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

/**
 * Service For Complect Logic which related with User
 */
class RoleService
{
    public function create(Request $request)
    {
        DB::transaction(static function () use ($request) {
            $permission = Permission::find($request->permission_id);
            $request->merge([
                'guard_name' => 'web',
            ]);
            $request->replace($request->except(['permission_id']));
            $rolePermission = (new Role)->create($request);
            $rolePermission->syncPermissions($permission);

            return $rolePermission;
        });
    }

    public function update($role, Request $request)
    {
        DB::transaction(static function () use ($request, $role) {
            $permission = Permission::find($request->permission_id);
            $request->merge([
                'guard_name' => 'web',
            ]);
            $request->replace($request->except(['permission_id']));
            $rolePermission = (new Role)->update($request, $role);
            $rolePermission->syncPermissions($permission);

            return $rolePermission;
        });
    }
}
