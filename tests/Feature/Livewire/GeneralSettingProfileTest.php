<?php

use App\Filament\Tenant\Pages\GeneralSetting;
use App\Models\Tenants\Profile;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

function generalSettingWithProfile(): array
{
    $user = User::first();
    (new Profile)->forceFill([
        'user_id' => $user->id,
        'phone' => '1',
        'address' => 'a',
        'locale' => 'en',
        'timezone' => 'UTC',
    ])->save();

    actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));

    return [$user, Livewire::test(GeneralSetting::class)->assertOk()];
}

test('saving a profile creates one profile row linked to the user', function () {
    [$user, $component] = generalSettingWithProfile();

    $component->set('profile.phone', '0812345678')->call('saveProfile');

    expect(Profile::where('user_id', $user->id)->count())->toBe(1)
        ->and(Profile::where('user_id', $user->id)->first()->phone)->toBe('0812345678');
});

test('saving a profile without a password keeps the current hash', function () {
    [$user, $component] = generalSettingWithProfile();
    $hash = $user->fresh()->password;

    $component->set('profile.name', 'Renamed')->set('profile.password', '')->call('saveProfile');

    expect($user->fresh()->name)->toBe('Renamed')
        ->and($user->fresh()->password)->toBe($hash);
});

test('saving a profile with a password rehashes it', function () {
    [$user, $component] = generalSettingWithProfile();

    $component->set('profile.password', 'new-password-123')
        ->set('profile.password_confirmation', 'new-password-123')
        ->call('saveProfile');

    expect(Hash::check('new-password-123', $user->fresh()->password))->toBeTrue();
});
