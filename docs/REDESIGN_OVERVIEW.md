# Lakasir Redesign Overview

## Project Summary

Replacing Filament Admin Panel with custom Livewire + Tailwind CSS UI while preserving all existing backend logic and database structure.

## Technology Stack

| Component | Current | Target |
|-----------|---------|--------|
| Admin UI | Filament 3.x | Livewire 3 + Volt |
| Styling | Filament Theme | Tailwind CSS |
| Frontend JS | Alpine (via Filament) | Alpine.js |
| Backend | Laravel 11.x | Unchanged |
| Database | MySQL | Unchanged |
| Multi-tenancy | stancl/tenancy | Unchanged |

## Figma Design Reference

**Main File**: [Redesign Lakasir](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-)

---

## Phase Overview

| Phase | Name | Duration | Status | Document |
|-------|------|----------|--------|----------|
| 1 | Foundation & Authentication | 2-3 days | Not Started | [PHASE_1.md](./phases/PHASE_1.md) |
| 2 | Dashboard & Common UI | 2-3 days | Not Started | [PHASE_2.md](./phases/PHASE_2.md) |
| 3 | POS/Cashier (Mobile) | 4-5 days | Not Started | [PHASE_3.md](./phases/PHASE_3.md) |
| 4 | POS/Cashier (Tablet) | 3-4 days | Not Started | [PHASE_4.md](./phases/PHASE_4.md) |
| 5 | Transaction Management | 2-3 days | Not Started | [PHASE_5.md](./phases/PHASE_5.md) |
| 6 | Category Management | 1-2 days | Not Started | [PHASE_6.md](./phases/PHASE_6.md) |
| 7 | Product Management | 3-4 days | Not Started | [PHASE_7.md](./phases/PHASE_7.md) |
| 8 | Member & User Management | 2-3 days | Not Started | [PHASE_8.md](./phases/PHASE_8.md) |
| 9 | Purchasing & Stock Opname | 2-3 days | Not Started | [PHASE_9.md](./phases/PHASE_9.md) |
| 10 | Receivables & Vouchers | 2 days | Not Started | [PHASE_10.md](./phases/PHASE_10.md) |
| 11 | Reporting | 2-3 days | Not Started | [PHASE_11.md](./phases/PHASE_11.md) |
| 12 | Final Cleanup & Testing | 2-3 days | Not Started | [PHASE_12.md](./phases/PHASE_12.md) |

---

---

## Multilanguage Support

**IMPORTANT**: All user-facing text in Blade templates and Livewire/Volt components must use Laravel's localization helper functions (`__('key')` or `@lang('key')`) instead of hardcoded strings.

### Translation Files
- English: `lang/en/`
- Indonesian: `lang/id/`
- Spanish: `lang/es/`

### Usage Examples
```blade
{{-- Correct --}}
<h1>{{ __('auth.welcome_back') }}</h1>
<p>{{ __('auth.sign_in_to_continue') }}</p>

{{-- Incorrect --}}
<h1>Welcome Back</h1>
<p>Sign in to your account to continue</p>
```

### Key Guidelines
1. Always use `__('file.key')` for translatable text
2. Add translations to all three language files (en, id, es)
3. Use descriptive translation keys (e.g., `auth.welcome_back` not `auth.text1`)
4. Include placeholders for dynamic text: `__('welcome_message', ['name' => $user->name])`

---

## Design Implementation Status

### Common UI Screens

| Screen | Figma | Status | Phase |
|--------|-------|--------|-------|
| Login | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-836) | Not Started | Phase 1 |
| Homepage/Dashboard | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-1204) | Not Started | Phase 2 |
| Landing Transaction | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2789) | Not Started | Phase 5 |
| Category | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2918) | Not Started | Phase 6 |
| Transaction History | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1497-3316) | Not Started | Phase 5 |
| See All Transactions | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-2847) | Not Started | Phase 5 |
| Transaction Detail | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-1871) | Not Started | Phase 5 |
| Settings Menu | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3250) | Not Started | Phase 2 |
| Simple Settings | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3407) | Not Started | Phase 2 |

### Cashier Mobile Screens

| Screen | Figma | Status | Phase |
|--------|-------|--------|-------|
| Product List | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=2-139) | Not Started | Phase 3 |
| Add to Cart Popup | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=106-834) | Not Started | Phase 3 |
| Cart Item State | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=147-357) | Not Started | Phase 3 |
| Cart List | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=153-1067) | Not Started | Phase 3 |
| Payment Method | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1226) | Not Started | Phase 3 |
| QR Payment | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1405) | Not Started | Phase 3 |
| Non-QR Payment | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2231) | Not Started | Phase 3 |
| Payment Success | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1632) | Not Started | Phase 3 |

### Cashier Tablet Screens

| Screen | Figma | Status | Phase |
|--------|-------|--------|-------|
| Product List + Cart Empty | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=96-572) | Not Started | Phase 4 |
| Add to Cart Popup Tablet | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-876) | Not Started | Phase 4 |
| Product List + Cart Items | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-517) | Not Started | Phase 4 |
| Cart Modal | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=225-1015) | Not Started | Phase 4 |

---

## Architecture Decisions

### Frontend Stack
- **Livewire 3**: Primary reactive framework
- **Volt**: Single-file component syntax
- **Tailwind CSS**: Utility-first styling
- **Alpine.js**: Client-side interactions

### Directory Structure

```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php          # Main authenticated layout
│   │   ├── guest.blade.php        # Public pages layout
│   │   └── auth.blade.php         # Authentication layout
│   ├── ui/
│   │   ├── button.blade.php
│   │   ├── input.blade.php
│   │   ├── select.blade.php
│   │   ├── modal.blade.php
│   │   ├── card.blade.php
│   │   ├── table.blade.php
│   │   ├── dropdown.blade.php
│   │   ├── toast.blade.php
│   │   ├── badge.blade.php
│   │   ├── avatar.blade.php
│   │   ├── skeleton.blade.php
│   │   └── pagination.blade.php
│   └── shared/
│       ├── sidebar.blade.php
│       ├── header.blade.php
│       └── mobile-nav.blade.php
├── livewire/
│   ├── pages/
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── dashboard.blade.php
│   │   └── settings/
│   ├── pos/
│   │   ├── product-list.blade.php
│   │   ├── cart.blade.php
│   │   └── payment.blade.php
│   ├── products/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── categories/
│   ├── members/
│   ├── users/
│   ├── roles/
│   ├── sellings/
│   ├── purchasings/
│   ├── stock-opnames/
│   ├── receivables/
│   ├── vouchers/
│   ├── payment-methods/
│   ├── suppliers/
│   └── reports/
└── livewire/ (Volt components)
    ├── pages/
    └── components/
```

### Backend Structure (Preserved)

All existing controllers, services, models, and repositories remain unchanged:

```
app/
├── Http/Controllers/Api/Tenants/
│   ├── Master/
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── MemberController.php
│   │   └── SupplierController.php
│   ├── Transaction/
│   │   ├── SellingController.php
│   │   └── DashboardController.php
│   └── ...
├── Services/
├── Repositories/
└── Models/
```

---

## Migration Strategy

### Parallel Development
During migration, both systems will coexist:
1. New Livewire components are created
2. Routes are added for new components
3. Once validated, Filament routes are removed
4. Filament package is removed after full migration

### Feature Flags
Optional: Use Laravel Pennant for gradual rollout:

```php
// Enable new UI for specific users
Feature::activate('new-ui', function ($user) {
    return $user->isBetaTester();
});
```

### Rollback Capability
- Keep Filament until full migration complete
- Use git branches for each phase
- Maintain database snapshots

---

## Testing Requirements

### Per-Phase Testing
| Test Type | Coverage Target |
|-----------|-----------------|
| Unit Tests | Services & Business Logic |
| Feature Tests | HTTP Endpoints |
| Livewire Tests | Component Behavior |
| Browser Tests | Critical User Flows |

### Critical Test Flows
1. Authentication (login/logout)
2. POS transaction (complete purchase)
3. Permission checks
4. Multi-tenancy isolation

---

## Dependencies to Remove

After full migration, remove:

```json
{
    "filament/filament": "^3.2",
    "aymanalhattami/filament-date-scopes-filter": "^1.0",
    "aymanalhattami/filament-page-with-sidebar": "^2.5",
    "tapp/filament-timezone-field": "^3.0"
}
```

## Dependencies to Keep

```json
{
    "php": "^8.1",
    "laravel/framework": "^11.9",
    "livewire/livewire": "^3.0",
    "livewire/volt": "^1.6",
    "spatie/laravel-permission": "6.9.0",
    "stancl/tenancy": "^3.6"
}
```

---

## Session Log

See [SESSION_LOG.md](./SESSION_LOG.md) for ongoing progress tracking.

---

## Quick Commands

```bash
# Run development server
npm run dev

# Run tests
php artisan pest

# Run specific test
php artisan pest --filter=FeatureTest

# Clear cache
php artisan optimize:clear

# Run migrations (if needed)
php artisan migrate
```

---

## Questions & Answers

**Q: Why Livewire instead of Inertia/Vue/React?**
A: Project already uses Livewire, team familiarity, faster development with PHP-only approach.

**Q: Will existing API be affected?**
A: No. All existing API controllers and routes remain unchanged. New UI will consume same APIs.

**Q: How to handle multi-tenancy?**
A: Same as current. stancl/tenancy works at middleware level, transparent to UI.

**Q: Can we use both old and new UI during migration?**
A: Yes. Different routes for each. Old routes can be removed when new UI is validated.
