# Phase 5: Transaction Management

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: Phase 3 & 4 (POS)

---

## Overview

This phase implements the transaction management screens including the transaction landing page, history list, and detail views. These screens allow users to view past transactions and their details.

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Landing Transaction | 1492-2789 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2789) |
| Transaction History | 1497-3316 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1497-3316) |
| See All Transactions | 1513-2847 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-2847) |
| Transaction Detail | 1513-1871 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-1871) |

---

## Prerequisites

- [ ] Phase 3 & 4 complete
- [ ] Base UI components available
- [ ] API endpoints functional

---

## Tasks

### 1. Landing Transaction Page

**File**: `resources/views/livewire/pages/transaction/landing.blade.php`

**Design Reference**: [Figma - Landing Transaction](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2789)

#### 1.1 Page Layout

```
┌─────────────────────────────────────┐
│  Welcome, [User Name]!              │
│  Today's Overview                   │
├─────────────────────────────────────┤
│  Stats Cards                         │
│  ┌─────────┐ ┌─────────┐           │
│  │ Sales   │ │ Orders  │           │
│  │ Today   │ │ Today   │           │
│  └─────────┘ └─────────┘           │
├─────────────────────────────────────┤
│  Quick Actions                       │
│  ┌─────────────────────────────────┐│
│  │ [New Sale] [View Reports]       ││
│  └─────────────────────────────────┘│
├─────────────────────────────────────┤
│  Recent Transactions                 │
│  ┌─────────────────────────────────┐│
│  │ TRX001 | Rp 50.000 | 10:30     ││
│  │ TRX002 | Rp 75.000 | 11:15     ││
│  │ TRX003 | Rp 30.000 | 14:20     ││
│  └─────────────────────────────────┘│
│  [See All Transactions →]           │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create landing page component
- [ ] Add welcome header
- [ ] Display today's stats
- [ ] Show recent transactions preview
- [ ] Add quick action buttons
- [ ] Link to full transaction list

---

### 2. Transaction History List

**File**: `resources/views/livewire/pages/transaction/index.blade.php`

**Design Reference**: [Figma - Transaction History](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1497-3316)

**Design Reference**: [Figma - See All](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-2847)

#### 2.1 Page Layout

```
┌─────────────────────────────────────┐
│  Transactions                       │
│  ┌─────────────────────────────┐   │
│  │ 🔍 Search transactions...   │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Filters                            │
│  [Date Range] [Status] [Payment]   │
├─────────────────────────────────────┤
│  Transaction List                   │
│  ┌─────────────────────────────┐   │
│  │ TRX-001                     │   │
│  │ John Doe                    │   │
│  │ Rp 150.000 | Cash           │   │
│  │ 15 Jan 2024, 10:30         │   │
│  │ [Completed ✓]               │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ TRX-002                     │   │
│  │ Jane Smith                  │   │
│  │ Rp 75.000 | QRIS            │   │
│  │ 15 Jan 2024, 11:15         │   │
│  │ [Pending ⏳]                │   │
│  └─────────────────────────────┘   │
│                                     │
│  [Load More]                        │
├─────────────────────────────────────┤
│  Summary                            │
│  Total: Rp 225.000 (2 transactions)│
└─────────────────────────────────────┘
```

#### 2.2 Search & Filters

**Features**:
- Search by transaction ID, customer name
- Date range picker (today, this week, this month, custom)
- Status filter (completed, pending, cancelled)
- Payment method filter
- Clear filters button

**Tasks**:
- [ ] Create search input
- [ ] Add date range picker
- [ ] Add status filter dropdown
- [ ] Add payment method filter
- [ ] Implement filter logic
- [ ] Add clear filters button

#### 2.3 Transaction List Item

**File**: `resources/views/components/transaction/list-item.blade.php`

**Props**:
- `id`: Transaction ID
- `customer`: Customer name
- `amount`: Total amount
- `paymentMethod`: Payment type
- `date`: Transaction date
- `status`: Transaction status
- `items`: Item count

**Features**:
- Clickable row
- Status badge with color
- Action buttons (view, print, void)

**Tasks**:
- [ ] Create list item component
- [ ] Add status badge
- [ ] Format currency and date
- [ ] Add quick action buttons
- [ ] Add click to view detail

#### 2.4 Pagination

**Features**:
- Infinite scroll or load more button
- Page numbers (desktop)
- Per-page selector

**Tasks**:
- [ ] Implement pagination UI
- [ ] Add load more button
- [ ] Preserve filters on pagination

---

### 3. Transaction Detail

**File**: `resources/views/livewire/pages/transaction/show.blade.php`

**Design Reference**: [Figma - Transaction Detail](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-1871)

#### 3.1 Page Layout

```
┌─────────────────────────────────────┐
│  ← Transaction Details              │
├─────────────────────────────────────┤
│  ┌─────────────────────────────┐   │
│  │ TRX-001                     │   │
│  │ Status: [Completed ✓]       │   │
│  │ 15 January 2024, 10:30:45  │   │
│  │ Cashier: John Doe           │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Customer                           │
│  ┌─────────────────────────────┐   │
│  │ Name: Jane Smith            │   │
│  │ Phone: 08123456789          │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Items                              │
│  ┌─────────────────────────────┐   │
│  │ Product A x2    Rp 50.000   │   │
│  │ Product B x1    Rp 25.000   │   │
│  │ Product C x3    Rp 75.000   │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Summary                            │
│  ┌─────────────────────────────┐   │
│  │ Subtotal        Rp 150.000  │   │
│  │ Discount        -Rp 10.000  │   │
│  │ Tax (10%)        Rp 14.000  │   │
│  │ ──────────────────────────  │   │
│  │ Total           Rp 154.000  │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Payment                            │
│  ┌─────────────────────────────┐   │
│  │ Method: Cash                │   │
│  │ Paid:           Rp 200.000  │   │
│  │ Change:          Rp 46.000  │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Notes                              │
│  ┌─────────────────────────────┐   │
│  │ Extra sauce please          │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Actions                            │
│  [Print Receipt] [Void Transaction]│
└─────────────────────────────────────┘
```

#### 3.2 Detail Sections

**Sections**:
1. Header - Transaction ID, status, date, cashier
2. Customer - Customer information (if selected)
3. Items - List of purchased items
4. Summary - Totals and calculations
5. Payment - Payment details
6. Notes - Transaction notes
7. Actions - Print, void, refund

**Tasks**:
- [ ] Create detail page layout
- [ ] Implement each section
- [ ] Add print receipt functionality
- [ ] Add void transaction (with confirmation)
- [ ] Add refund functionality (if applicable)

#### 3.3 Status Badge

**Statuses**:
- `completed` - Green
- `pending` - Yellow
- `cancelled` - Red
- `refunded` - Gray

**Tasks**:
- [ ] Create status badge component
- [ ] Add appropriate colors
- [ ] Add icons

---

### 4. Transaction Actions

#### 4.1 Print Receipt

**Features**:
- Reprint receipt
- Print to different printer
- Print preview

**Tasks**:
- [ ] Add print button
- [ ] Integrate with receipt template
- [ ] Handle print errors

#### 4.2 Void Transaction

**Features**:
- Void reason input
- Confirmation modal
- Supervisor approval (if enabled)
- Audit log entry

**Tasks**:
- [ ] Create void confirmation modal
- [ ] Add void reason textarea
- [ ] Implement void API call
- [ ] Update transaction status

#### 4.3 Refund (Optional)

**Features**:
- Select items to refund
- Calculate refund amount
- Refund method selection

**Tasks**:
- [ ] Create refund modal
- [ ] Add item selection
- [ ] Calculate refund totals
- [ ] Process refund

---

## Data Structure

### Transaction Model Fields

```php
// Existing Selling model
'table' => 'sellings',
'fillable' => [
    'code',
    'total',
    'total_pay',
    'total_change',
    'discount',
    'discount_type',
    'tax',
    'status',
    'payment_method_id',
    'member_id',
    'user_id',
    'notes',
    'created_at',
]
```

### Transaction Item Fields

```php
// Existing SellingDetail model
'table' => 'selling_details',
'fillable' => [
    'selling_id',
    'product_id',
    'price_unit_id',
    'quantity',
    'price',
    'subtotal',
    'discount',
    'notes',
]
```

---

## API Endpoints (Existing)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/tenant/transaction/sellings` | GET | List transactions |
| `/api/tenant/transaction/sellings/{id}` | GET | Get transaction detail |
| `/api/tenant/transaction/sellings/{id}/void` | POST | Void transaction |
| `/api/tenant/transaction/sellings/{id}/refund` | POST | Refund transaction |

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Volt::route('/transactions', 'pages/transaction/index')
        ->name('transactions.index');

    Volt::route('/transactions/landing', 'pages/transaction/landing')
        ->name('transactions.landing');

    Volt::route('/transactions/{id}', 'pages/transaction/show')
        ->name('transactions.show');
});
```

---

## Files to Create

### Volt Pages
```
resources/views/livewire/pages/transaction/
├── landing.blade.php
├── index.blade.php
└── show.blade.php
```

### Components
```
resources/views/components/transaction/
├── list-item.blade.php
├── status-badge.blade.php
├── summary-card.blade.php
└── void-modal.blade.php
```

---

## Testing Checklist

### Unit Tests
- [ ] Test transaction filtering
- [ ] Test search functionality
- [ ] Test date range filter

### Feature Tests
- [ ] Test transaction list page
- [ ] Test transaction detail page
- [ ] Test void transaction
- [ ] Test print receipt

### Browser Tests
- [ ] Test full transaction browsing
- [ ] Test search and filter interaction
- [ ] Test transaction detail viewing

---

## Acceptance Criteria

- [ ] Landing page shows today's overview
- [ ] Recent transactions display correctly
- [ ] Transaction list is filterable
- [ ] Search finds transactions
- [ ] Date range filter works
- [ ] Status filter works
- [ ] Transaction detail shows all information
- [ ] Print receipt works
- [ ] Void transaction works (with confirmation)
- [ ] All pages are responsive
- [ ] Pagination works correctly

---

## Notes

- Consider real-time updates for pending transactions
- Void transactions should be auditable
- Consider role-based permissions for void/refund actions
- Export functionality can be added (CSV/PDF)

---

## Next Phase

After completing this phase, proceed to [Phase 6: Category Management](./PHASE_6.md)