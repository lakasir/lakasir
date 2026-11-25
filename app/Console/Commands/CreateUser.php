<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenants\User;
use App\Services\ProvisionCompany;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    protected $signature = 'app:create-user';

    protected $description = 'Create the owner user and, when none exists, the first company';

    public function handle(ProvisionCompany $provision): int
    {
        $user = User::create([
            'name' => $this->ask('name'),
            'email' => $this->ask('email'),
            'password' => bcrypt($this->ask('password')),
            'is_owner' => true,
        ]);

        // A local install never starts a trial: self-hosting is free.
        $company = Company::first()
            ?? $provision->create(['name' => $this->ask('shop name'), 'owner' => $user]);

        $company->makeCurrent();
        $company->users()->syncWithoutDetaching([$user->id]);

        $this->info('User created, open '.config('app.url').'/member/login');

        return self::SUCCESS;
    }
}
