<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Illuminate\Database\QueryException;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('a company only sees its own products', function () {
    $first = mockCompany('Toko Satu');
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id]);

    $second = mockCompany('Toko Dua');
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id]);
    Product::factory()->create(['category_id' => $category->id]);

    $second->makeCurrent();
    expect(Product::count())->toBe(2)
        ->and(Product::withoutGlobalScopes()->count())->toBe(3);

    $first->makeCurrent();
    expect(Product::count())->toBe(1)
        ->and(Product::withoutGlobalScopes()->count())->toBe(3);
});

test('creating a model without a company fills the current one in', function () {
    $company = mockCompany();

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->company_id)->toBe($company->id);
});

test('settings are per company, including their cache', function () {
    $first = mockCompany('Toko Satu');
    Setting::set('currency', 'IDR');

    $second = mockCompany('Toko Dua');
    Setting::set('currency', 'USD');

    expect(Setting::get('currency'))->toBe('USD');

    $first->makeCurrent();
    expect(Setting::get('currency'))->toBe('IDR');
});

test('member codes and emails repeat across companies but not within one', function () {
    $first = mockCompany('Toko Satu');
    Member::create(['name' => 'Budi', 'code' => 'MBR001', 'email' => 'budi@mail.com']);

    expect(fn () => Member::create(['name' => 'Budi 2', 'code' => 'MBR001', 'email' => 'budi@mail.com']))
        ->toThrow(QueryException::class);

    $second = mockCompany('Toko Dua');
    Member::create(['name' => 'Budi', 'code' => 'MBR001', 'email' => 'budi@mail.com']);

    expect(Member::count())->toBe(1)
        ->and(Member::withoutGlobalScopes()->count())->toBe(2);
});
