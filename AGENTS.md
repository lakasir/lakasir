# AGENTS.md - Lakasir POS Development Guidelines

## Project Overview
Lakasir is a multi-tenant Point of Sale (POS) application built with Laravel 11 (Laravel 10 structure), Filament v3, Livewire v3, and Pest testing framework.

## Build/Lint/Test Commands

### PHP
```bash
composer install                      # Install PHP dependencies
composer run dev                    # Run dev server via Laravel
php artisan serve                   # Alternative dev server
php artisan test                    # Run all Pest tests
php artisan test --filter=testName  # Run specific test (use after changes)
php artisan test tests/Feature/ExampleTest.php  # Run test file
```

### Frontend
```bash
npm install                         # Install Node dependencies
npm run dev                         # Development build with Vite
npm run build                       # Production build
```

### Database
```bash
php artisan migrate                 # Run migrations
php artisan migrate --path=database/migrations/tenant --seed
php artisan db:seed                 # Run seeders
```

### Filament/Livewire
```bash
php artisan filament:assets         # Publish Filament assets
php artisan livewire:publish --assets
php artisan filament:optimize       # Production optimization
```

## Code Style Guidelines

### PHP
- Follow PSR-12 standards
- Use PHP 8.2+ features: typed properties, explicit return types, constructor property promotion
- Always use curly braces for control structures
- Avoid `mixed` type - use specific types
- Prefer constructor injection over facades (except config/log)
- No static business logic
- Use PHPDoc blocks over inline comments
- Enum keys should be TitleCase (e.g., `FavoritePerson`)

### Architecture
- **Clean Architecture**: Business logic lives in Services, not Controllers
- Controllers only orchestrate: request → service → response
- Use FormRequest classes for validation
- Use Policies for authorization
- No raw SQL - prefer Eloquent ORM and relationships
- Prevent N+1 queries with eager loading

### Database
- Always use migrations
- Define proper Eloquent relationships with return type hints
- Add indexes for foreign keys and frequently filtered columns
- Use `$query->latest()->limit(10)` for limiting eager loaded records

### Testing (Pest)
- All tests use Pest PHP (not PHPUnit directly)
- Create tests: `php artisan make:test --pest <name>`
- Use factories for model creation
- Tests live in `tests/Feature/` and `tests/Unit/`
- Use `mockTenant()` for multi-tenant testing
- Run minimal tests with filters after changes

### Imports
- Use `use Forms\Components` for form fields
- Use `use Tables\Columns` for table columns
- Prefer `Model::query()` over `DB::`
- Import mock functions: `use function Pest\Laravel\mock;`

### Naming Conventions
- Descriptive names: `isRegisteredForDiscounts` not `discount()`
- Resources: `app/Filament/Resources/`
- Livewire: `App\Livewire` namespace (not `App\Http\Livewire`)
- Form requests: `StoreUserRequest`, `UpdateUserRequest`

### Error Handling
- Use Laravel's validation and authorization features
- Never expose stack traces in production
- Use specific assertions: `assertForbidden()`, `assertNotFound()`

### Blade/Tailwind
- 2-space indentation for Blade, JS, CSS
- Tailwind classes sorted automatically
- Use `dark:` prefix for dark mode support
- Use gap utilities for spacing, not margins
- 120 char line length for Blade templates

### Configuration
- Never use `env()` outside config files
- Use `config('app.name')` not `env('APP_NAME')`
- Feature flags via Laravel Pennant

### Livewire
- Components need single root element
- Use `wire:model.live` for real-time updates (deferred by default in v3)
- Use `$this->dispatch()` not `emit`
- Use `wire:key` in loops: `wire:key="item-{{ $item->id }}"`
- Layout path: `components.layouts.app`

### Filament
- Resources auto-generate pages in `app/Filament/Resources/PostResource/Pages/`
- Use `->relationship()` for select/checkbox options
- Use `make()` methods for component initialization
- Implement `FilamentUser` contract for access control

### API
- Use Eloquent API Resources for JSON transformation
- Use named routes and `route()` function
- Standard response format with `success`, `data`, `message`

## Multi-tenancy
- Uses Stancl Tenancy with separate databases per tenant
- Testing uses `RefreshDatabaseWithTenant` trait
- Check existing tenant-aware patterns before implementing
