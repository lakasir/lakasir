# Laravel Dusk Tests for Cashier Alpine.js Behavior

This document explains the Laravel Dusk tests created to test the Alpine.js functionality in the Cashier page.

## Setup

1. **Install Laravel Dusk** (already done):
   ```bash
   composer require laravel/dusk --dev
   php artisan dusk:install
   ```

2. **Fix Blade File Issues**: The current `resources/views/filament/tenant/pages/cashier.blade.php` has JavaScript syntax errors in the `@script` section that need to be resolved before running the tests.

3. **Add Data Attributes**: The tests expect certain data attributes on elements. Add these to the blade file:
   - Calculator buttons: `data-calculator="1"`, `data-calculator="backspace"`, etc.
   - Payment method divs: `:data-payment-method="paymentMethod.name"`
   - Cart quantity inputs: `data-qty-input="{{ $item->id }}"`
   - Discount inputs: `data-discount-input="{{ $item->id }}"`

## Running the Tests

```bash
# Run all Dusk tests
php artisan dusk

# Run specific test file
php artisan dusk tests/Browser/CashierAlpineTest.php

# Run with headless disabled (to see browser)
php artisan dusk --browse
```

## Test Coverage

The `tests/Browser/CashierAlpineTest.php` file includes tests for:

1. **Calculator Functionality**: Tests number input, backspace, and "no changes" button
2. **Payment Method Selection**: Tests switching between payment methods and UI updates
3. **QR Scanner Modal**: Tests modal opening and closing
4. **Fullscreen Button**: Verifies button presence
5. **Cart Quantity Input**: Tests quantity updates with Livewire debounce
6. **Discount Input**: Tests masked input formatting
7. **Payment Shortcuts**: Verifies shortcut button generation

## Test Structure

Each test:
- Creates necessary test data (users, products, cart items, settings)
- Uses `browse()` function with Browser instance
- Interacts with Alpine.js components through DOM selectors
- Asserts expected behavior and UI state changes

## Notes

- Tests use Pest syntax with global `browse()` function
- Requires ChromeDriver (automatically installed with `dusk:install`)
- Tests run in headless mode by default
- Some Alpine.js features (like fullscreen API) can't be fully tested in headless mode
- Livewire interactions are tested through DOM assertions rather than server-side logic