<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Tenants\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        if (config('database.default') == 'sanctum') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        User::get()->each(fn (User $user) => $this->assignRoleToUser($user));

        $permissions = $this->getPermissions();
        $permissions->each(fn ($roles) => $this->savePermission($roles));
        if (config('database.default') == 'sanctum') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        User::first()->assignRole(Role::admin);
    }

    private function crudRolePermission(): array
    {
        return [
            [
                'role' => [Role::admin],
                'permissions' => [
                    'user' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web'],
                    ],
                    'category' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'product' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'product stock' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'member' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'selling' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'selling method' => [
                        'permission' => [
                            'set',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'payment method' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'cash drawer' => [
                        'permission' => [
                            'open', 'enable', 'close',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'printer' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'secure initial price' => [
                        'permission' => [
                            'enable', 'verify',
                        ],
                        'guard' => ['sanctum'],
                    ],
                    'currency' => [
                        'permission' => [
                            'u',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'role' => [
                        'permission' => [
                            'c', 'r', 'u', 'd',
                        ],
                        'guard' => ['web'],
                    ],
                    'permission' => [
                        'permission' => [
                            'r',
                        ],
                        'guard' => ['web'],
                    ],
                    'web app' => [
                        'permission' => [
                            'access',
                        ],
                        'guard' => ['web'],
                    ],
                    'cashier report' => [
                        'permission' => [
                            'generate',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'selling report' => [
                        'permission' => [
                            'generate',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'product report' => [
                        'permission' => [
                            'generate',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'import product' => [
                        'permission' => [
                            '',
                        ],
                        'guard' => ['web'],
                    ],
                    'default tax' => [
                        'permission' => [
                            'set',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                    'about' => [
                        'permission' => [
                            'r', 'u',
                        ],
                        'guard' => ['web', 'sanctum'],
                    ],
                ],
            ],
        ];
    }

    private function normalizeCrudPermission()
    {
        $normalize = [];
        foreach ($this->crudRolePermission() as $permissions) {
            foreach ($permissions['permissions'] as $feature => $crud) {
                $actions = [];
                for ($i = 0; $i < count($crud['permission']); $i++) {
                    $action = '';
                    switch ($crud['permission'][$i]) {
                        case 'c':
                            $action = "create $feature";
                            break;
                        case 'r':
                            $action = "read $feature";
                            break;
                        case 'u':
                            $action = "update $feature";
                            break;
                        case 'd':
                            $action = "delete $feature";
                            break;
                        default:
                            $action = $crud['permission'][$i]." $feature";
                            break;
                    }
                    $actions[$i] = $action;
                }
                foreach ($actions as $action) {
                    $normalize[] = [
                        'role' => $permissions['role'],
                        'action' => $action,
                        'guard' => $crud['guard'],
                    ];
                }
            }
        }

        return $normalize;
    }

    private function getPermissions(): Collection
    {
        return collect(array_merge($this->normalizeCrudPermission(), [

        ]));
    }

    private function savePermission($roles): void
    {
        foreach ($roles['guard'] as $guard) {
            $permission = Permission::firstOrCreate(['name' => $roles['action'], 'guard_name' => $guard]);
            $this->givePermissionToRole($roles['role'], $permission);
        }
    }

    private function givePermissionToRole($role, $permission): void
    {
        /** @var ModelsRole $role */
        $role = ModelsRole::where('name', $role[0])->firstOrCreate(['name' => $role[0]]);
        $role->permissions()->syncWithoutDetaching($permission);
    }

    /**
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function assignRoleToUser(User $user)
    {
        $role = ModelsRole::inRandomOrder()->first();
        $user->syncRoles($role);
    }
}
