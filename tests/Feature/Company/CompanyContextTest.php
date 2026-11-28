<?php

use App\Constants\Role;
use App\Http\Middleware\SetApiCompany;
use App\Models\Company;
use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use App\Notifications\StockRunsOut;
use App\Services\ProvisionCompany;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Feature;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabaseWithTenant::class);

/**
 * A second shop with its own owner. Provisioning leaves it current; tests call
 * Company::forgetCurrent() to start a request the way a fresh one starts.
 */
function secondShop(): array
{
    $owner = User::create(['email' => 'dua@mail.com', 'password' => bcrypt('password')]);
    $company = app(ProvisionCompany::class)->create(['name' => 'Toko Dua', 'owner' => $owner]);

    return [$company, $owner];
}

test('a query with no company sees no rows instead of every shop', function () {
    Category::factory()->create();

    Company::forgetCurrent();

    expect(Category::count())->toBe(0)
        ->and(Category::withoutGlobalScopes()->count())->toBe(1);
});

test('the api enters the token owner\'s shop when the host names none', function () {
    $first = Company::first();
    [$second, $secondOwner] = secondShop();

    Company::forgetCurrent();

    actingAs($secondOwner, 'sanctum')->getJson('/api/about')
        ->assertOk()
        ->assertJsonPath('data.shop_name', 'Toko Dua');

    expect(Company::currentId())->toBe($second->id)
        ->and(About::withoutGlobalScopes()->where('company_id', $first->id)->exists())->toBeFalse();
});

test('the api refuses a user who belongs to no shop', function () {
    $stranger = User::create(['email' => 'stranger@mail.com', 'password' => bcrypt('password')]);

    Company::forgetCurrent();

    actingAs($stranger, 'sanctum')->getJson('/api/about')->assertForbidden();
});

test('the company header picks one of the user\'s shops, and only theirs', function () {
    $first = Company::first();
    $owner = User::first();
    [$second] = secondShop();

    Company::forgetCurrent();
    actingAs($owner, 'sanctum')
        ->getJson('/api/about', [SetApiCompany::HEADER => $second->id])
        ->assertForbidden();

    $second->users()->attach($owner->id);

    Company::forgetCurrent();
    actingAs($owner, 'sanctum')
        ->getJson('/api/about', [SetApiCompany::HEADER => $second->id])
        ->assertOk()
        ->assertJsonPath('data.shop_name', 'Toko Dua');

    Company::forgetCurrent();
    actingAs($owner, 'sanctum')->getJson('/api/about')->assertOk();
    expect(Company::currentId())->toBe($first->id);
});

test('a shop domain the token owner does not belong to is refused', function () {
    [$second] = secondShop();
    $second->update(['domain' => 'dua.lakasir.test']);

    // A real bearer token, not actingAs(): the POS app sends no session, so the
    // host is matched before anything knows who is asking.
    $token = User::first()->createToken('pos')->plainTextToken;

    Company::forgetCurrent();

    getJson('http://dua.lakasir.test/api/about', ['Authorization' => "Bearer {$token}"])
        ->assertForbidden();
});

test('login with no shop on the host enters the user\'s shop and returns its permissions', function () {
    Company::forgetCurrent();

    $response = postJson('/api/auth/login', ['email' => User::first()->email, 'password' => 'password'])
        ->assertOk();

    expect($response->json('data.permissions'))->not->toBeEmpty();
});

test('the shop api is not in the web group, whose csrf check bearer requests cannot pass', function () {
    $routes = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'api/'));

    expect($routes)->not->toBeEmpty();

    // Laravel switches the csrf check off under tests, so a request would pass
    // either way: assert on the route's own middleware instead.
    foreach ($routes as $route) {
        expect(in_array('web', $route->gatherMiddleware(), true))->toBeFalse($route->uri());
    }
});

test('a report download enters the shop picked in the session', function () {
    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);
    $company = Company::first();

    Company::forgetCurrent();

    actingAs($owner)
        ->withSession(['company_id' => $company->id])
        ->get('/member/selling-report/generate?start_date=2026-01-01&end_date=2026-01-31')
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

test('feature flags are per shop', function () {
    $first = Company::first();
    [$second] = secondShop();

    $first->makeCurrent();
    Feature::deactivate('voucher');

    $second->makeCurrent();
    expect(Feature::active('voucher'))->toBeTrue();

    $first->makeCurrent();
    expect(Feature::active('voucher'))->toBeFalse();
});

test('app:create-user gives an owner of an existing shop its admin role', function () {
    $company = Company::first();

    artisan('app:create-user')
        ->expectsQuestion('name', 'Kasir Baru')
        ->expectsQuestion('email', 'baru@mail.com')
        ->expectsQuestion('password', 'password')
        ->assertSuccessful();

    $company->makeCurrent();

    expect(User::where('email', 'baru@mail.com')->first()->hasRole(Role::admin))->toBeTrue();
});

test('the stock alert reaches only the staff of the shop that runs out', function () {
    Notification::fake();

    $firstOwner = User::first();
    Product::factory()->create(['category_id' => Category::factory()->create()->id, 'stock' => 0]);

    [, $secondOwner] = secondShop();

    artisan('app:fcm')->assertSuccessful();

    Notification::assertSentTo($firstOwner, StockRunsOut::class);
    Notification::assertNotSentTo($secondOwner, StockRunsOut::class);
});
