# AGENTS.md - Coding Agent Instructions

## Project Overview

This is a multi-tenant Point of Sale (POS) application built with Laravel 11.x, Filament 3.x admin panel, and Livewire/Volt for reactive UI. Uses `stancl/tenancy` for multi-tenancy (database per tenant pattern).

## Build/Lint/Test Commands

### Dependencies
```bash
composer install          # Install PHP dependencies
npm install              # Install frontend dependencies
```

### Build
```bash
npm run dev              # Development build (Vite)
npm run build            # Production build
```

### Test Commands
```bash
php artisan test                                    # Run all tests
php artisan test --filter=TestName                  # Run specific test by name
php artisan test tests/Feature/Path/To/Test.php     # Run single test file
vendor/bin/pest                                     # Run Pest directly
vendor/bin/pest --filter="test name here"           # Run specific test
```

### Database Setup
```bash
php artisan key:generate
php artisan migrate --path=database/migrations/tenant --seed
php artisan filament:assets
php artisan livewire:publish --assets
```

### Code Formatting
- Blade: Uses `.bladeformatterrc.json` (2-space indent, 120 line length)
- EditorConfig: Defined in `.editorconfig`

## Code Style Guidelines

### Import Ordering
```php
// Internal classes first, then external packages
use App\Models\Tenants\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
```

### Naming Conventions
- **Classes:** PascalCase (e.g., `ProductController`, `VoucherService`)
- **Methods:** camelCase (e.g., `filterCategory()`, `applicable()`)
- **Variables:** camelCase (e.g., `$product`, `$cartItems`)
- **Database columns:** snake_case (e.g., `product_id`, `created_at`)
- **Constants:** UPPER_CASE (e.g., `ROLE_ADMIN`)

### Formatting
- **PHP:** 4 spaces indentation
- **Blade/JS/CSS:** 2 spaces indentation
- **Line length:** 120 characters max
- **Arrays:** Short syntax `[]` not `array()`
- **Braces:** PSR-12 style (opening brace on same line)

### Type Annotations
- Use PHP 8.1+ typed properties and return types
- Always declare parameter types and return types
```php
public function index(): JsonResponse
public function validate(string $attribute, mixed $value, Closure $fail): void
```

### Models
```php
protected $guarded = ['id'];              // Prefer guarded over fillable
protected $appends = ['computed_field'];   // For accessors

public function product(): BelongsTo       // Relationship with return type
{
    return $this->belongsTo(Product::class);
}
```

### Controllers
- Use Form Requests for validation (e.g., `ProductRequest`)
- Use `ApiResponseService` for consistent JSON responses
- Controllers extend base `Controller` class

### Error Handling
```php
try {
    DB::beginTransaction();
    // operations
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}
```

### Testing (Pest)
```php
<?php

use App\Models\Tenants\User;
use Illuminate\Http\Response;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('can create product', function () {
    $user = User::first();
    actingAs($user)->postJson('/api/master/product', $data)
        ->assertStatus(Response::HTTP_CREATED);
});
```

## Architecture Patterns

### Multi-tenancy
- Uses `stancl/tenancy` with database-per-tenant
- Tenant models in `app/Models/Tenants/`
- Use `RefreshDatabaseWithTenant` trait in tests
- Call `mockTenant()` for tenant context in tests

### Filament Resources
- Located in `app/Filament/Tenant/Resources/`
- Use `HasTranslatableResource` trait for navigation labels

### Feature Flags
Use Laravel Pennant:
```php
if (feature(FeatureClass::class)) {
    // feature enabled
}
```

### Helper Functions
Global helpers in `app/helpers.php`:
- `hasFeatureAndPermission()`
- `can()`
- `price_format()`

## Key Directories
- `app/Http/Controllers/Api/Tenants/` - API controllers
- `app/Filament/Tenant/Resources/` - Filament admin resources
- `app/Services/` - Business logic services
- `app/Policies/` - Authorization policies
- `database/migrations/tenant/` - Tenant migrations
- `tests/Feature/` - Feature tests

## Before Committing
1. Run `php artisan test` to ensure all tests pass
2. Verify code follows PSR-12 formatting
3. Use `$guarded = ['id']` for new models
4. Add proper type hints to all methods