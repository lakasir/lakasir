<?php

use App\Models\Company;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Gate;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('an owner of one shop is not an owner of another they belong to', function () {
    $first = Company::currentId();

    $user = User::firstOrFail();
    $user->companies()->updateExistingPivot($first, ['is_owner' => true]);

    $second = mockCompany('Toko Kedua');
    $user->companies()->syncWithoutDetaching([$second->id => ['is_owner' => false]]);

    $second->makeCurrent();
    expect($user->fresh()->isOwner())->toBeFalse();

    Company::find($first)->makeCurrent();
    expect($user->fresh()->isOwner())->toBeTrue();
});

test('the owner gate only opens inside the shop that made them an owner', function () {
    $first = Company::currentId();
    $user = User::firstOrFail();
    $user->companies()->updateExistingPivot($first, ['is_owner' => true]);

    $second = mockCompany('Toko Kedua');
    $user->companies()->syncWithoutDetaching([$second->id => ['is_owner' => false]]);

    Company::find($first)->makeCurrent();
    expect(Gate::forUser($user->fresh())->allows('delete user'))->toBeTrue();

    $second->makeCurrent();
    expect(Gate::forUser($user->fresh())->allows('delete user'))->toBeFalse();
});

test('the user list shows only the current shop members', function () {
    $first = Company::currentId();
    $owner = User::firstOrFail();
    $owner->companies()->updateExistingPivot($first, ['is_owner' => true]);

    $second = mockCompany('Toko Kedua');
    $stranger = User::create([
        'name' => 'Orang Lain',
        'email' => 'orang@lain.test',
        'password' => bcrypt('password'),
    ]);
    $stranger->companies()->syncWithoutDetaching([$second->id => ['is_owner' => true]]);

    Company::find($first)->makeCurrent();
    $visible = User::query()
        ->whereHas('companies', fn ($q) => $q->whereKey(Company::currentId()))
        ->pluck('email')
        ->all();

    expect($visible)->toContain($owner->email)
        ->and($visible)->not->toContain('orang@lain.test');
});
