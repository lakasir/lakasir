# Phase 11: Reporting

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: All previous phases

---

## Overview

Implement comprehensive reporting functionality for sales, products, cashiers, and purchases with export capabilities.

---

## Tasks

### 1. Reports Landing Page

**File**: `resources/views/livewire/pages/reports/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Reports                                               │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Date Range: [Today ▼]  │ [Jan 1] - [Jan 31, 2024]│  │
│  └──────────────────────────────────────────────────┘  │
├─────────────────────────────────────────────────────────┤
│  Report Cards                                          │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │ 📊 Sales    │ │ 📦 Products │ │ 👥 Cashiers │      │
│  │ Report      │ │ Report      │ │ Report      │      │
│  │             │ │             │ │             │      │
│  │ [View →]    │ │ [View →]    │ │ [View →]    │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
│  ┌─────────────┐ ┌─────────────┐                      │
│  │ 🛒 Purchase │ │ 📉 Stock    │                      │
│  │ Report      │ │ Report      │                      │
│  │             │ │             │                      │
│  │ [View →]    │ │ [View →]    │                      │
│  └─────────────┘ └─────────────┘                      │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create reports landing page
- [ ] Add date range selector
- [ ] Create report cards

### 2. Sales Report

**File**: `resources/views/livewire/pages/reports/sales.blade.php`

**Sections**:
1. **Summary Cards**
   - Total Revenue
   - Total Transactions
   - Average Transaction Value
   - Growth (vs previous period)

2. **Chart**
   - Daily/Weekly/Monthly sales
   - Line or bar chart
   - Comparison with previous period

3. **Transaction Breakdown**
   - By payment method
   - By category
   - By hour of day

4. **Top Performers**
   - Top selling products
   - Top customers
   - Busiest hours

**Tasks**:
- [ ] Create sales report page
- [ ] Implement summary calculations
- [ ] Add chart integration (Chart.js)
- [ ] Create breakdown tables
- [ ] Add export functionality

### 3. Product Report

**File**: `resources/views/livewire/pages/reports/products.blade.php`

**Sections**:
1. **Product Performance**
   - Best sellers by quantity
   - Best sellers by revenue
   - Worst performers
   - Dead stock (no sales)

2. **Category Analysis**
   - Sales by category
   - Category growth

3. **Stock Analysis**
   - Low stock items
   - Overstocked items
   - Stock turnover rate

**Tasks**:
- [ ] Create product report page
- [ ] Implement sorting/filtering
- [ ] Add stock analysis
- [ ] Add export functionality

### 4. Cashier Report

**File**: `resources/views/livewire/pages/reports/cashiers.blade.php`

**Sections**:
1. **Per Cashier Summary**
   - Transactions count
   - Total sales
   - Average transaction
   - Refunds/Voids

2. **Comparison Table**
   - Side-by-side comparison
   - Performance ranking

3. **Transaction Log**
   - Filter by cashier
   - Time-based analysis

**Tasks**:
- [ ] Create cashier report page
- [ ] Implement per-user stats
- [ ] Add comparison view
- [ ] Add export functionality

### 5. Purchase Report

**File**: `resources/views/livewire/pages/reports/purchasings.blade.php`

**Sections**:
1. **Purchase Summary**
   - Total purchases
   - Total spent
   - Average order value

2. **Supplier Analysis**
   - Purchases by supplier
   - Payment status

3. **Product Purchases**
   - Most purchased items
   - Cost analysis

**Tasks**:
- [ ] Create purchase report page
- [ ] Implement supplier analysis
- [ ] Add cost breakdown
- [ ] Add export functionality

### 6. Export Functionality

**Features**:
- Export to PDF
- Export to Excel/CSV
- Email reports (optional)
- Scheduled reports (optional)

**Tasks**:
- [ ] Integrate Laravel Excel for exports
- [ ] Create PDF templates
- [ ] Add export buttons
- [ ] Handle large datasets

---

## Date Range Options

| Preset | Description |
|--------|-------------|
| Today | Current date |
| Yesterday | Previous date |
| This Week | Mon-Sun of current week |
| Last Week | Previous week Mon-Sun |
| This Month | Current month |
| Last Month | Previous month |
| This Year | Current year |
| Custom | User-selected range |

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/reports', \App\Livewire\Pages\Reports\Index::class)
        ->name('reports.index');
    Route::get('/reports/sales', \App\Livewire\Pages\Reports\Sales::class)
        ->name('reports.sales');
    Route::get('/reports/products', \App\Livewire\Pages\Reports\Products::class)
        ->name('reports.products');
    Route::get('/reports/cashiers', \App\Livewire\Pages\Reports\Cashiers::class)
        ->name('reports.cashiers');
    Route::get('/reports/purchasings', \App\Livewire\Pages\Reports\Purchasings::class)
        ->name('reports.purchasings');
    
    Route::get('/reports/export/{type}', \App\Http\Controllers\ReportExportController::class)
        ->name('reports.export');
});
```

---

## Files to Create

```
app/Livewire/Pages/Reports/
├── Index.php
├── Sales.php
├── Products.php
├── Cashiers.php
└── Purchasings.php

resources/views/livewire/pages/reports/
├── index.blade.php
├── sales.blade.php
├── products.blade.php
├── cashiers.blade.php
└── purchasings.blade.php

resources/views/components/reports/
├── summary-card.blade.php
├── chart-container.blade.php
├── date-range-picker.blade.php
└── export-button.blade.php
```

---

## Dependencies

```bash
# For charts
npm install chart.js

# For exports
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
```

---

## Acceptance Criteria

- [ ] Reports landing page displays
- [ ] Date range selection works
- [ ] Sales report shows correct data
- [ ] Product report shows correct data
- [ ] Cashier report shows correct data
- [ ] Purchase report shows correct data
- [ ] Charts render correctly
- [ ] PDF export works
- [ ] Excel/CSV export works
- [ ] Large datasets handled
- [ ] Reports are printable

---

## Next Phase

[Phase 12: Final Cleanup & Testing](./PHASE_12.md)