<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Permission::truncate();
        Role::truncate();
        DB::statement('TRUNCATE TABLE role_has_permissions');
        DB::statement('TRUNCATE TABLE model_has_permissions');
        DB::statement('TRUNCATE TABLE model_has_roles');
        $permissions = config('permission_seeder');
        foreach ($permissions as $key => $value) {
            if ($key == 'role') {
                foreach ($value as $k => $v) {
                    Role::create(['name' => $v]);
                }
            }
            if ($key == 'permissions') {
                foreach ($value as $k => $v) {
                    $permission = Permission::create(['name' => $k]);
                    foreach ($v as $s) {
                        Role::where('name', $s)->first()->givePermissionTo($permission);
                    }
                }
            }
        }

        $users = User::where('id', 1)->get();
        foreach ($users as $user) {
            $user->syncRoles(Role::first());
        }
    }
}
