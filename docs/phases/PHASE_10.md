# Phase 10: Receivables & Vouchers

**Duration**: 2 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation)

---

## Overview

Implement receivables tracking (money owed to/from business) and voucher management.

---

## Tasks

### 1. Receivables Management

#### 1.1 Receivables List

**File**: `resources/views/livewire/pages/receivables/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Receivables                           [+ New Receivable]│
├─────────────────────────────────────────────────────────┤
│  Tabs: [Payable] [Receivable]                          │
├─────────────────────────────────────────────────────────┤
│  Contact   │ Amount    │ Paid     │ Due      │ Status │
│  ───────────────────────────────────────────────────── │
│  John Doe  │ Rp 500K   │ Rp 200K  │ 01 Feb   │ Partial│
│  Jane S.   │ Rp 1M     │ Rp 0     │ 15 Feb   │ Pending│
│  Supplier A│ Rp 2M     │ Rp 2M    │ 10 Jan   │ Paid   │
├─────────────────────────────────────────────────────────┤
│  Total Outstanding: Rp 1.3M                            │
└─────────────────────────────────────────────────────────┘
```

**Types**:
- **Receivable**: Money owed TO the business (customer debt)
- **Payable**: Money owed BY the business (supplier debt)

**Tasks**:
- [ ] Create receivables list
- [ ] Implement tabs for payable/receivable
- [ ] Add status indicators
- [ ] Show outstanding totals

#### 1.2 Receivable Form

**Fields**:
- Type (receivable/payable) *
- Contact name *
- Contact phone
- Total amount *
- Due date *
- Notes
- Initial payment (optional)

**Tasks**:
- [ ] Create receivable form
- [ ] Handle type selection
- [ ] Add form validation

#### 1.3 Payment Recording

**Features**:
- Record payments
- Track payment history
- Update remaining balance
- Auto-close when fully paid

**Tasks**:
- [ ] Create payment form modal
- [ ] Track payment history
- [ ] Update status automatically

---

### 2. Voucher Management

#### 2.1 Vouchers List

**File**: `resources/views/livewire/pages/vouchers/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Vouchers                              [+ New Voucher]   │
├─────────────────────────────────────────────────────────┤
│  Code     │ Name      │ Value   │ Used │ Expiry │ Status│
│  ────────────────────────────────────────────────────── │
│  NEWYEAR24│ New Year  │ Rp 50K  │ 15/20│ 31 Jan │ Active│
│  MEMBER10 │ Member    │ 10%     │ 5/∞  │ Never  │ Active│
│  EXPIRED  │ Old       │ Rp 25K  │ 0/10 │ 01 Jan │ Expired│
├─────────────────────────────────────────────────────────┤
│  Showing 1-10 of 10                                    │
└─────────────────────────────────────────────────────────┘
```

**Voucher Types**:
- Fixed amount (e.g., Rp 50.000)
- Percentage (e.g., 10% off)
- Free product/item

**Tasks**:
- [ ] Create voucher list
- [ ] Show usage statistics
- [ ] Add status filters

#### 2.2 Voucher Form

**Fields**:
- Voucher code * (auto-generate option)
- Name *
- Description
- Type * (fixed/percentage/product)
- Value * (amount or percentage)
- Minimum purchase
- Maximum uses (unlimited option)
- Valid from *
- Valid until *
- Product restriction (optional)
- Active status

**Tasks**:
- [ ] Create voucher form
- [ ] Add type-based fields
- [ ] Handle date validation
- [ ] Generate random code

#### 2.3 Voucher Usage

**Features**:
- View usage history
- See which transactions used voucher
- Export voucher data

**Tasks**:
- [ ] Create usage history modal
- [ ] Link to transactions

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/receivables', \App\Livewire\Pages\Receivables\Index::class)
        ->name('receivables.index');
    Route::get('/receivables/{id}', \App\Livewire\Pages\Receivables\Show::class)
        ->name('receivables.show');
    
    Route::get('/vouchers', \App\Livewire\Pages\Vouchers\Index::class)
        ->name('vouchers.index');
});
```

---

## Files to Create

```
app/Livewire/Pages/
├── Receivables/
│   ├── Index.php
│   └── Show.php
└── Vouchers/
    └── Index.php

resources/views/livewire/pages/
├── receivables/
│   ├── index.blade.php
│   └── show.blade.php
└── vouchers/
    └── index.blade.php

resources/views/components/
├── receivables/
│   ├── payment-form.blade.php
│   └── payment-history.blade.php
└── vouchers/
    ├── usage-badge.blade.php
    └── usage-history.blade.php
```

---

## Acceptance Criteria

### Receivables
- [ ] List displays payables and receivables
- [ ] Create receivable/payable
- [ ] Record payments
- [ ] Payment history displays
- [ ] Status updates automatically
- [ ] Outstanding totals calculate correctly

### Vouchers
- [ ] Voucher list displays
- [ ] Create voucher with all types
- [ ] Code generation works
- [ ] Usage tracking works
- [ ] Expiry handling works
- [ ] Voucher redemption in POS works

---

## Next Phase

[Phase 11: Reporting](./PHASE_11.md)