<?php

namespace App\Console\Commands;

use App\Constants\Role;
use App\Models\Tenants\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateUser extends Command
{
    protected $signature = 'app:create-user';

    protected $description = 'Create user for standalone lakasir';

    public function handle()
    {
        if (config('tenancy.central_domains')[0] === null) {
            $user = User::create([
                'name' => $this->ask('name'),
                'email' => $this->ask('email'),
                'password' => bcrypt($this->ask('password')),
                'is_owner' => true,
            ]);
            Artisan::call('db:seed', [
                '--class' => 'PermissionSeeder',
            ]);
            $user->assignRole(Role::admin);

            $this->info('User created successfully, please open '.config('app.url').'/member/login');
        } else {
            $this->error("You can't run this command on multi tenant");
        }
    }
}
