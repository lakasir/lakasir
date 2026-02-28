# Phase 9: Purchasing & Stock Opname

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: Phase 7 (Products)

---

## Overview

Implement purchasing (buying from suppliers) and stock opname (inventory audit) functionality.

---

## Tasks

### 1. Purchasing Management

#### 1.1 Purchase List

**File**: `resources/views/livewire/pages/purchasings/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Purchases                             [+ New Purchase]  │
├─────────────────────────────────────────────────────────┤
│  Filters: [Supplier ▼] [Status ▼] [Date Range]         │
├─────────────────────────────────────────────────────────┤
│  Code     │ Supplier  │ Date    │ Total    │ Status    │
│  ────────────────────────────────────────────────────── │
│  PO-001   │ Supplier A│ 15 Jan │ Rp 2.5M  │ Received  │
│  PO-002   │ Supplier B│ 16 Jan │ Rp 1.2M  │ Pending   │
├─────────────────────────────────────────────────────────┤
│  Showing 1-10 of 25                                    │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create purchase list component
- [ ] Implement filters
- [ ] Add status badges

#### 1.2 Purchase Form

**Sections**:
1. **Header Info**
   - Purchase code (auto)
   - Supplier selection *
   - Purchase date *
   - Notes

2. **Items**
   - Product selection
   - Quantity
   - Unit price (cost)
   - Subtotal (auto)
   - Add item button

3. **Summary**
   - Subtotal
   - Discount
   - Tax
   - Grand total
   - Payment status

**Tasks**:
- [ ] Create purchase form
- [ ] Add dynamic item rows
- [ ] Calculate totals
- [ ] Handle supplier selection

#### 1.3 Receive Goods

**Features**:
- Mark purchase as received
- Update product stock
- Record received quantity
- Handle partial receipts

**Tasks**:
- [ ] Create receive goods modal
- [ ] Update stock on receive
- [ ] Handle partial quantities

---

### 2. Stock Opname (Inventory Audit)

#### 2.1 Stock Opname List

**File**: `resources/views/livewire/pages/stock-opnames/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Stock Opname                        [+ New Stock Opname]│
├─────────────────────────────────────────────────────────┤
│  Reference│ Date    │ Items │ Variance │ Status        │
│  ────────────────────────────────────────────────────── │
│  SO-001   │ 15 Jan  │ 45    │ +2       │ Completed     │
│  SO-002   │ 01 Feb  │ 50    │ -5       │ Draft         │
├─────────────────────────────────────────────────────────┤
│  Showing 1-5 of 5                                      │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create stock opname list
- [ ] Show variance indicators
- [ ] Implement status filters

#### 2.2 Stock Opname Form

**Flow**:
1. Create new stock opname
2. Select products to count
3. Enter actual quantities
4. System calculates variance
5. Confirm to adjust stock

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Stock Opname - SO-001                                  │
│  Date: 15 January 2024                   [Draft]        │
├─────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────┐   │
│  │ 🔍 Search products...         [+ Add All Items]│   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Product    │ Unit │ System │ Actual │ Variance        │
│  ───────────────────────────────────────────────────── │
│  Product A  │ pcs  │ 50     │ [48 ]  │ -2 🔻          │
│  Product B  │ pcs  │ 100    │ [105]  │ +5 🔺          │
│  Product C  │ pcs  │ 25     │ [25 ]  │ 0 ✓            │
├─────────────────────────────────────────────────────────┤
│  Summary:                                              │
│  Total Items: 3                                        │
│  Positive Variance: +5 pcs                             │
│  Negative Variance: -2 pcs                             │
├─────────────────────────────────────────────────────────┤
│  Notes:                                                │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Damaged items removed from shelf                │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  [Save Draft]                    [Confirm & Adjust]     │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create stock opname form
- [ ] Add product search
- [ ] Calculate variances
- [ ] Implement confirmation
- [ ] Adjust stock on confirm

#### 2.3 Stock Opname History

**Features**:
- View past stock opnames
- See variance reports
- Export to PDF/Excel

**Tasks**:
- [ ] Create history view
- [ ] Add export functionality

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/purchasings', \App\Livewire\Pages\Purchasings\Index::class)
        ->name('purchasings.index');
    Route::get('/purchasings/create', \App\Livewire\Pages\Purchasings\Form::class)
        ->name('purchasings.create');
    Route::get('/purchasings/{id}', \App\Livewire\Pages\Purchasings\Show::class)
        ->name('purchasings.show');
    
    Route::get('/stock-opnames', \App\Livewire\Pages\StockOpnames\Index::class)
        ->name('stock-opnames.index');
    Route::get('/stock-opnames/create', \App\Livewire\Pages\StockOpnames\Form::class)
        ->name('stock-opnames.create');
    Route::get('/stock-opnames/{id}', \App\Livewire\Pages\StockOpnames\Show::class)
        ->name('stock-opnames.show');
});
```

---

## Files to Create

```
app/Livewire/Pages/
├── Purchasings/
│   ├── Index.php
│   ├── Form.php
│   └── Show.php
└── StockOpnames/
    ├── Index.php
    ├── Form.php
    └── Show.php

resources/views/livewire/pages/
├── purchasings/
│   ├── index.blade.php
│   ├── form.blade.php
│   └── show.blade.php
└── stock-opnames/
    ├── index.blade.php
    ├── form.blade.php
    └── show.blade.php
```

---

## Acceptance Criteria

### Purchasing
- [ ] Purchase list displays correctly
- [ ] Create purchase with items
- [ ] Supplier selection works
- [ ] Receive goods updates stock
- [ ] Partial receipts handled

### Stock Opname
- [ ] Stock opname list displays
- [ ] Create stock opname
- [ ] Add products to count
- [ ] Enter actual quantities
- [ ] Variance calculated correctly
- [ ] Confirm adjusts stock
- [ ] Draft saving works

---

## Next Phase

[Phase 10: Receivables & Vouchers](./PHASE_10.md)