<?php

use App\Models\Tenants\Setting;
use Illuminate\Support\Facades\Cache;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(fn () => Cache::clear());

test('a cached false is returned as false, not as a cache miss', function () {
    Setting::set('cash_drawer_enabled', false);

    // First call stores false in the cache; the second must read it back.
    expect(Setting::get('cash_drawer_enabled', true))->toBeFalse()
        ->and(Setting::get('cash_drawer_enabled', true))->toBeFalse();
});

test('a cached zero or empty string is not treated as missing', function () {
    Setting::set('default_tax', 0);
    Setting::set('currency', '');

    expect((float) Setting::get('default_tax', 10))->toBe(0.0)
        ->and(Setting::get('currency', 'IDR'))->toBe('');
});

test('a missing key falls back to the default', function () {
    expect(Setting::get('never_set', 'fallback'))->toBe('fallback');
});

test('set replaces the cached value', function () {
    Setting::set('default_tax', 5);
    Setting::get('default_tax');
    Setting::set('default_tax', 11);

    expect(Setting::get('default_tax'))->toEqual(11);
});
