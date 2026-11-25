<?php

use App\Models\Company;
use App\Models\Tenants\Category;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use App\Services\ProvisionCompany;
use Spatie\Permission\Models\Role;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('provisioning a shop gives it its own payments, permissions and category', function () {
    $owner = User::first();

    $company = app(ProvisionCompany::class)->create([
        'name' => 'Toko Dua',
        'owner' => $owner,
        'trial_days' => 30,
        'provisioned_by' => 'cloud',
    ]);

    expect($company->provisioned_by)->toBe('cloud')
        ->and($company->onTrial())->toBeTrue()
        ->and($company->trialDaysLeft())->toBe(30);

    $company->makeCurrent();

    expect(PaymentMethod::count())->toBeGreaterThan(0)
        ->and(Category::where('name', 'UMUM')->exists())->toBeTrue()
        ->and($owner->fresh()->hasRole('admin'))->toBeTrue()
        ->and($owner->companies()->whereKey($company->id)->exists())->toBeTrue();
});

test('a locally provisioned shop has no trial, so it is never charged', function () {
    $company = app(ProvisionCompany::class)->create(['name' => 'Toko Lokal']);

    expect($company->provisioned_by)->toBe('local')
        ->and($company->trial_ends_at)->toBeNull()
        ->and($company->onTrial())->toBeFalse()
        ->and($company->trialExpired())->toBeFalse();
});

test('a trial that has run out reports expired', function () {
    $company = Company::create(['name' => 'Toko Trial', 'trial_ends_at' => now()->subDay()]);

    expect($company->onTrial())->toBeFalse()
        ->and($company->trialExpired())->toBeTrue();
});

test('switching shop changes which rows are visible', function () {
    $owner = User::first();
    $first = Company::currentId();

    Product::factory()->create();

    $second = app(ProvisionCompany::class)->create(['name' => 'Toko Dua', 'owner' => $owner]);
    Product::factory()->create();

    Company::find($second->id)->makeCurrent();
    expect(Product::count())->toBe(1);

    Company::find($first)->makeCurrent();
    expect(Product::count())->toBe(1)
        ->and(Product::withoutGlobalScopes()->count())->toBe(2);
});

test('each shop has its own admin role, granted separately', function () {
    $owner = User::first();
    $first = Company::currentId();

    $second = app(ProvisionCompany::class)->create(['name' => 'Toko Dua', 'owner' => $owner]);

    // One role row per shop, not one shared row.
    expect(Role::where('name', 'admin')->pluck('company_id')->all())
        ->toEqualCanonicalizing([$first, $second->id]);

    // The owner holds admin in that shop only, and Spatie's team filter keeps
    // the two grants apart.
    $owner->refresh();
    expect($owner->hasRole('admin'))->toBeTrue();

    Company::find($second->id)->makeCurrent();
    $owner->refresh();
    expect($owner->hasRole('admin'))->toBeTrue();

    $rolesByCompany = DB::table('model_has_roles')
        ->where('model_id', $owner->id)
        ->pluck('company_id')
        ->all();

    expect($rolesByCompany)->toEqualCanonicalizing([$first, $second->id]);
});
