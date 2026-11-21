<?php

use App\Models\Tenants\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(fn () => Cache::clear());

/**
 * Drop the row behind a cached setting, so a read that still answers can only
 * have come from the cache (or from the default, when the cache treated the
 * falsy value as a miss).
 */
function forgetRow(string $key): void
{
    DB::table('settings')->where('key', $key)->delete();
}

test('a cached false is served from the cache', function () {
    Setting::set('cash_drawer_enabled', false);
    Setting::get('cash_drawer_enabled');

    forgetRow('cash_drawer_enabled');

    expect(Setting::get('cash_drawer_enabled', true))->toBeFalsy();
});

test('a cached zero is served from the cache', function () {
    Setting::set('default_tax', 0);
    Setting::get('default_tax');

    forgetRow('default_tax');

    expect((float) Setting::get('default_tax', 10))->toBe(0.0);
});

test('a cached empty string is served from the cache', function () {
    Setting::set('currency', '');
    Setting::get('currency');

    forgetRow('currency');

    expect(Setting::get('currency', 'IDR'))->toBe('');
});

test('a key that was never set falls back to the default', function () {
    expect(Setting::get('never_set', 'fallback'))->toBe('fallback');
});

test('set replaces the cached value', function () {
    Setting::set('default_tax', 5);
    Setting::get('default_tax');
    Setting::set('default_tax', 11);

    expect(Setting::get('default_tax'))->toEqual(11);
});
