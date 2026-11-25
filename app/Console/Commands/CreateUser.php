<?php

namespace App\Console\Commands;

use App\Constants\Role;
use App\Models\Company;
use App\Models\Tenants\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateUser extends Command
{
    protected $signature = 'app:create-user';

    protected $description = 'Create the owner user and, when none exists, the first company';

    public function handle(): int
    {
        $company = Company::first() ?? Company::create([
            'name' => $this->ask('shop name'),
        ]);

        $company->makeCurrent();

        $user = User::create([
            'name' => $this->ask('name'),
            'email' => $this->ask('email'),
            'password' => bcrypt($this->ask('password')),
            'is_owner' => true,
        ]);

        $user->companies()->attach($company->id);

        Artisan::call('db:seed', [
            '--class' => 'PermissionSeeder',
            '--force' => true,
        ]);

        $user->assignRole(Role::admin);

        $this->info('User created, open '.config('app.url').'/member/login');

        return self::SUCCESS;
    }
}
