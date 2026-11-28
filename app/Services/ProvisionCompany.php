<?php

namespace App\Services;

use App\Constants\Role;
use App\Models\Company;
use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Artisan;

/**
 * The one place a company is created. lakasir-cloud provisioning, the panel's
 * CompanyResource and `app:create-user` all go through here, so a shop is
 * identical whichever door it came in.
 */
class ProvisionCompany
{
    /**
     * @param  array{name: string, domain?: ?string, owner?: ?User, trial_days?: ?int, provisioned_by?: string}  $data
     */
    public function create(array $data): Company
    {
        $company = Company::create([
            'name' => $data['name'],
            'domain' => $data['domain'] ?? null,
            'enabled' => true,
            'provisioned_by' => $data['provisioned_by'] ?? 'local',
            'trial_ends_at' => isset($data['trial_days'])
                ? now()->addDays($data['trial_days'])
                : null,
        ]);

        $company->makeCurrent();

        $this->seed($company);

        if ($owner = $data['owner'] ?? null) {
            $this->attachOwner($company, $owner);
        }

        return $company;
    }

    /**
     * Rows for a new company_id, zero DDL: payments, permissions and the
     * default category the POS needs before it can ring anything up.
     */
    private function seed(Company $company): void
    {
        foreach (['PaymentMethodSeeder', 'PermissionSeeder'] as $seeder) {
            Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
        }

        Category::firstOrCreate(['name' => 'UMUM']);

        About::firstOrCreate(
            ['shop_name' => $company->name],
            ['business_type' => 'retail'],
        );
    }

    private function attachOwner(Company $company, User $owner): void
    {
        $owner->companies()->syncWithoutDetaching([
            $company->id => ['is_owner' => true],
        ]);

        $owner->assignRole(Role::admin);
    }
}
