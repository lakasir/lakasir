# Phase 7: Product Management

**Duration**: 3-4 days  
**Status**: Not Started  
**Dependencies**: Phase 6 (Categories)

---

## Overview

Implement full product CRUD functionality with stock management, pricing, and image handling.

---

## Tasks

### 1. Product List Page

**File**: `resources/views/livewire/pages/products/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Products                              [+ Add Product]   │
├─────────────────────────────────────────────────────────┤
│  Filters: [Category ▼] [Status ▼] [Stock ▼]            │
│  ┌─────────────────────────────────────────────────┐   │
│  │ 🔍 Search products by name, SKU, or barcode...  │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Image │ Name         │ Category │ Stock │ Price │ Acts │
│  ─────────────────────────────────────────────────────  │
│  [img] │ Product A    │ Food     │ 45    │ 25K   │ ⋮   │
│  [img] │ Product B    │ Drinks   │ 0     │ 15K   │ ⋮   │
│  [img] │ Product C    │ Snacks   │ 150   │ 10K   │ ⋮   │
├─────────────────────────────────────────────────────────┤
│  Showing 1-25 of 150                    [<] 1 2 3 [>]   │
└─────────────────────────────────────────────────────────┘
```

**Features**:
- Data table with sorting
- Category filter dropdown
- Stock status filter (in stock, low, out)
- Search by name/SKU/barcode
- Bulk actions (activate, deactivate, delete)
- Export to CSV/Excel

**Tasks**:
- [ ] Create product list component
- [ ] Implement data table
- [ ] Add sorting functionality
- [ ] Implement filters
- [ ] Add search with debounce
- [ ] Add pagination
- [ ] Add bulk selection

### 2. Product Create/Edit Form

**File**: `resources/views/livewire/pages/products/form.blade.php`

**Form Sections**:

#### 2.1 Basic Information
- Product name *
- SKU (auto-generated or manual)
- Barcode (scan or enter)
- Category * (dropdown)
- Description

#### 2.2 Pricing
- Base price (cost)
- Selling price
- Unit prices (multiple pricing)
  - Unit name
  - Conversion factor
  - Selling price

#### 2.3 Stock
- Initial stock
- Minimum stock threshold
- Stock alert enabled

#### 2.4 Images
- Primary image upload
- Gallery images (multiple)
- Image drag-to-reorder

#### 2.5 Additional
- Weight (optional)
- Dimensions (optional)
- Active/Inactive toggle

**Tasks**:
- [ ] Create multi-section form
- [ ] Implement form validation
- [ ] Add dynamic unit price rows
- [ ] Handle image upload
- [ ] Add image preview
- [ ] Implement autosave (optional)

### 3. Product Detail View

**File**: `resources/views/livewire/pages/products/show.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  ← Product Details                          [Edit] [⋮]  │
├─────────────────────────────────────────────────────────┤
│  ┌──────────┐  Product Name                              │
│  │          │  Category: Food                            │
│  │  IMAGE   │  Status: Active                            │
│  │          │  SKU: PRD-001                              │
│  └──────────┘  Barcode: 8991234567890                    │
├─────────────────────────────────────────────────────────┤
│  Pricing                                                │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Base Price: Rp 20.000                           │   │
│  │ Selling Price: Rp 25.000                        │   │
│  │ ─────────────────────────────────────────────── │   │
│  │ Units:                                          │   │
│  │ • Regular (1x): Rp 25.000                       │   │
│  │ • Large (1.5x): Rp 35.000                       │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Stock                                                  │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Current Stock: 45                               │   │
│  │ Minimum: 10                                     │   │
│  │ Status: ✓ In Stock                              │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Stock History                                          │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Type    │ Qty │ Date       │ Note              │   │
│  │ Purchase│ +50 │ 2024-01-15 │ Restock           │   │
│  │ Sale    │ -5  │ 2024-01-16 │ TRX-001           │   │
│  │ Adjust  │ +2  │ 2024-01-17 │ Correction        │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Gallery                                                │
│  [Img1] [Img2] [Img3]                                  │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create detail page layout
- [ ] Display all product info
- [ ] Show price units
- [ ] Show stock status
- [ ] Display stock history
- [ ] Show image gallery

### 4. Stock Management

#### 4.1 Stock Adjustment Modal

```
┌─────────────────────────────────────┐
│  Adjust Stock                    [×]│
├─────────────────────────────────────┤
│  Current Stock: 45                  │
│                                     │
│  Adjustment Type:                   │
│  ○ Add Stock                        │
│  ○ Remove Stock                     │
│  ○ Set to Value                     │
│                                     │
│  Quantity: [____]                   │
│                                     │
│  Reason:                            │
│  ┌─────────────────────────────┐   │
│  │ Restock, Damaged, Expired...│   │
│  └─────────────────────────────┘   │
│                                     │
│  New Stock: 50                      │
├─────────────────────────────────────┤
│  [Cancel]              [Confirm]    │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create stock adjustment modal
- [ ] Add adjustment type selection
- [ ] Calculate new stock preview
- [ ] Add reason selection/input
- [ ] Update stock via API

### 5. Print Labels

**File**: `resources/views/livewire/pages/products/print-labels.blade.php`

**Features**:
- Select products for label printing
- Label template selection
- Print preview
- Print to thermal printer

**Tasks**:
- [ ] Create label template
- [ ] Add product selection
- [ ] Implement print functionality

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/products', \App\Livewire\Pages\Products\Index::class)
        ->name('products.index');
    Route::get('/products/create', \App\Livewire\Pages\Products\Form::class)
        ->name('products.create');
    Route::get('/products/{id}/edit', \App\Livewire\Pages\Products\Form::class)
        ->name('products.edit');
    Route::get('/products/{id}', \App\Livewire\Pages\Products\Show::class)
        ->name('products.show');
    Route::get('/products/print-labels', \App\Livewire\Pages\Products\PrintLabels::class)
        ->name('products.print-labels');
});
```

---

## Files to Create

```
app/Livewire/Pages/Products/
├── Index.php
├── Form.php
├── Show.php
└── PrintLabels.php

resources/views/livewire/pages/products/
├── index.blade.php
├── form.blade.php
├── show.blade.php
└── print-labels.blade.php

resources/views/components/products/
├── table-row.blade.php
├── form-sections.blade.php
├── price-unit-input.blade.php
├── stock-badge.blade.php
└── stock-history.blade.php
```

---

## API Endpoints (Existing)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/tenant/master/products` | GET | List products |
| `/api/tenant/master/products` | POST | Create product |
| `/api/tenant/master/products/{id}` | GET | Get product |
| `/api/tenant/master/products/{id}` | PUT | Update product |
| `/api/tenant/master/products/{id}` | DELETE | Delete product |
| `/api/tenant/master/products/{id}/stock` | POST | Adjust stock |

---

## Acceptance Criteria

- [ ] Product list displays all products
- [ ] Filtering by category works
- [ ] Search finds products
- [ ] Create product saves correctly
- [ ] Edit product updates correctly
- [ ] Delete product works with confirmation
- [ ] Stock adjustment works
- [ ] Multiple pricing units save correctly
- [ ] Image upload works
- [ ] Print labels functionality works

---

## Next Phase

[Phase 8: Member & User Management](./PHASE_8.md)