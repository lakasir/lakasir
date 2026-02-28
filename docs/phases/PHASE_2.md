# Phase 2: Dashboard & Common UI

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation & Authentication)

---

## Overview

This phase implements the main dashboard and settings pages. The dashboard serves as the landing page after login, displaying key metrics and quick actions.

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Homepage/Dashboard | 1470-1204 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-1204) |
| Settings Menu | 1513-3250 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3250) |
| Simple Settings | 1513-3407 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3407) |

---

## Prerequisites

- [ ] Phase 1 complete
- [ ] All base UI components available
- [ ] Authentication working
- [ ] Sidebar navigation functional

---

## Tasks

### 1. Dashboard Page

**File**: `resources/views/livewire/pages/dashboard.blade.php`

**Livewire Component**: `app/Livewire/Pages/Dashboard.php`

#### 1.1 Dashboard Layout

**Design**: Responsive grid with cards and widgets

**Structure**:
```
┌─────────────────────────────────────────────────────────────┐
│  Header: Welcome Message + Date                            │
├─────────────────────────────────────────────────────────────┤
│  Stats Cards (4 columns)                                     │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐           │
│  │ Sales   │ │ Orders  │ │ Product │ │ Customer│           │
│  │ Today   │ │ Today   │ │ Count   │ │ Count   │           │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘           │
├─────────────────────────────────────────────────────────────┤
│  Quick Actions                                               │
│  ┌─────────────────────────────────────────────┐           │
│  │ [New Sale] [Add Product] [View Reports]     │           │
│  └─────────────────────────────────────────────┘           │
├─────────────────────────────────────────────────────────────┤
│  Main Content Grid                                           │
│  ┌─────────────────────────┬─────────────────────────┐    │
│  │ Recent Transactions     │ Today's Best Seller      │    │
│  │ (List)                 │ (List)                   │    │
│  └─────────────────────────┴─────────────────────────┘    │
│  ┌─────────────────────────┬─────────────────────────┐    │
│  │ Low Stock Alert         │ Expired Products         │    │
│  │ (List)                  │ (List)                   │    │
│  └─────────────────────────┴─────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create dashboard page component
- [ ] Implement responsive grid layout
- [ ] Add welcome header with date
- [ ] Create stats cards component

#### 1.2 Stats Cards Widget

**File**: `resources/views/components/dashboard/stats-card.blade.php`

**Props**:
- `title`: card title
- `value`: main value
- `icon`: optional icon
- `trend`: optional trend percentage
- `trendDirection`: up, down, neutral
- `color`: primary, success, warning, danger

**Data to Display**:
1. **Today's Sales**: Total sales amount for today
2. **Today's Orders**: Count of transactions today
3. **Total Products**: Count of active products
4. **Total Customers**: Count of registered members

**Tasks**:
- [ ] Create stats card component
- [ ] Add icon support
- [ ] Add trend indicator
- [ ] Implement loading skeleton

#### 1.3 Quick Actions Bar

**Actions**:
- New Sale (POS) - primary button
- Add Product - secondary button
- View Reports - secondary button
- Stock Opname - secondary button

**Tasks**:
- [ ] Create quick actions component
- [ ] Add icon buttons
- [ ] Link to respective pages
- [ ] Responsive button layout

#### 1.4 Recent Transactions Widget

**File**: `resources/views/components/dashboard/recent-transactions.blade.php`

**API Endpoint**: `/api/tenant/transactions/recent`

**Display**:
- Transaction ID
- Customer name
- Total amount
- Time
- Status badge
- Quick view link

**Tasks**:
- [ ] Create recent transactions component
- [ ] Implement real-time updates (optional polling)
- [ ] Add pagination for "See All"
- [ ] Format currency and time

#### 1.5 Best Selling Products Widget

**File**: `resources/views/components/dashboard/best-sellers.blade.php`

**API Endpoint**: `/api/tenant/reports/products/best-selling`

**Data Source**: Existing `TodaysBestSellingProduct` widget from Filament

**Display**:
- Product image
- Product name
- Quantity sold
- Revenue

**Tasks**:
- [ ] Create best sellers component
- [ ] Add product images
- [ ] Show quantity and revenue
- [ ] Link to product detail

#### 1.6 Low Stock Alert Widget

**File**: `resources/views/components/dashboard/low-stock.blade.php`

**API Endpoint**: `/api/tenant/products/low-stock`

**Display**:
- Product name
- Current stock
- Minimum threshold
- Quick restock link

**Tasks**:
- [ ] Create low stock component
- [ ] Add urgency indicators (color coding)
- [ ] Link to stock management

#### 1.7 Expired Products Widget

**File**: `resources/views/components/dashboard/expired-products.blade.php`

**Data Source**: Existing `ExpiredProduct` widget from Filament

**Display**:
- Product name
- Expiration date
- Days until expiration (or days expired)
- Link to product

**Tasks**:
- [ ] Create expired products component
- [ ] Add color coding by urgency
- [ ] Link to product management

---

### 2. Settings Pages

**File**: `resources/views/livewire/pages/settings/general.blade.php`

#### 2.1 Settings Navigation

**Design Reference**: [Figma - Settings Menu](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3250)

**Structure**:
```
┌─────────────────────────────────────────────────────────────┐
│  Settings                                                   │
├─────────────────────────────────────────────────────────────┤
│  Sidebar          │ Main Content                            │
│  ┌──────────────┐ │                                        │
│  │ General      │ │ [Settings Form/Content]                │
│  │ Users        │ │                                        │
│  │ Roles        │ │                                        │
│  │ Permissions  │ │                                        │
│  │ Printer      │ │                                        │
│  │ About        │ │                                        │
│  └──────────────┘ │                                        │
└─────────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create settings layout with sidebar
- [ ] Implement settings navigation
- [ ] Add active state highlighting
- [ ] Mobile responsive design

#### 2.2 General Settings

**Design Reference**: [Figma - Simple Settings](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3407)

**Data Source**: Existing `GeneralSetting.php` Filament page

**Settings Categories**:

1. **Store Information**
   - Store name
   - Store address
   - Store phone
   - Store email
   - Store logo upload

2. **Receipt Settings**
   - Receipt header text
   - Receipt footer text
   - Show logo on receipt
   - Paper size (58mm/80mm)

3. **Currency Settings**
   - Currency symbol
   - Currency position (before/after)
   - Decimal places

4. **Tax Settings**
   - Enable tax
   - Tax rate (%)
   - Tax included in price

5. **Transaction Settings**
   - Default payment method
   - Require customer info
   - Receipt auto-print

**Tasks**:
- [ ] Create General Settings form
- [ ] Add form sections
- [ ] Implement logo upload
- [ ] Add form validation
- [ ] Save settings (update .env or database)
- [ ] Add success toast notification

#### 2.3 User Settings

**File**: `resources/views/livewire/pages/settings/users.blade.php`

**Features**:
- User list table
- Create/Edit user modal
- Role assignment
- Status toggle (active/inactive)

**Tasks**:
- [ ] Create user list component
- [ ] Add create/edit modal
- [ ] Implement role dropdown
- [ ] Add user status toggle

#### 2.4 Role & Permission Settings

**File**: `resources/views/livewire/pages/settings/roles.blade.php`

**Features**:
- Role list
- Create/Edit role modal
- Permission matrix (checkbox grid)
- Permission categories

**Permission Categories** (based on spatie/laravel-permission):
- Transactions (view, create, update, delete)
- Products (view, create, update, delete)
- Members (view, create, update, delete)
- Reports (view)
- Settings (view, update)
- Users (view, create, update, delete)

**Tasks**:
- [ ] Create role list component
- [ ] Add create/edit modal
- [ ] Implement permission matrix
- [ ] Add permission groups
- [ ] Save role with permissions

#### 2.5 Printer Settings

**File**: `resources/views/livewire/pages/settings/printer.blade.php`

**Data Source**: Existing `Printer.php` Filament page

**Features**:
- Printer connection test
- Paper size configuration
- Print preview
- USB printer setup

**Tasks**:
- [ ] Create printer settings form
- [ ] Add printer connection test
- [ ] Implement print preview
- [ ] Add paper size options

#### 2.6 About Page

**File**: `resources/views/livewire/pages/settings/about.blade.php`

**Content**:
- Application name
- Version
- License
- Developer credits
- Support links

**Tasks**:
- [ ] Create about page
- [ ] Add version display
- [ ] Add support links

---

### 3. Profile Page

**File**: `resources/views/livewire/pages/settings/profile.blade.php`

**Features**:
- User avatar upload
- Profile information form
- Password change form
- Language selection
- Timezone selection

**Tasks**:
- [ ] Create profile page
- [ ] Add avatar upload
- [ ] Implement password change
- [ ] Add timezone selector
- [ ] Add language selector

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/dashboard', \App\Livewire\Pages\Dashboard::class)
        ->name('dashboard');
    
    Route::prefix('settings')->group(function () {
        Route::get('/', \App\Livewire\Pages\Settings\General::class)
            ->name('settings.general');
        Route::get('/users', \App\Livewire\Pages\Settings\Users::class)
            ->name('settings.users');
        Route::get('/roles', \App\Livewire\Pages\Settings\Roles::class)
            ->name('settings.roles');
        Route::get('/printer', \App\Livewire\Pages\Settings\Printer::class)
            ->name('settings.printer');
        Route::get('/about', \App\Livewire\Pages\Settings\About::class)
            ->name('settings.about');
        Route::get('/profile', \App\Livewire\Pages\Settings\Profile::class)
            ->name('settings.profile');
    });
});
```

---

## Dashboard Widgets Data Requirements

### Stats Card Data

**API Endpoint**: `/api/tenant/dashboard/stats`

```json
{
    "sales_today": 1500000,
    "orders_today": 45,
    "total_products": 234,
    "total_customers": 89,
    "sales_trend": "+12.5",
    "orders_trend": "-3.2"
}
```

**Controller Method**: `DashboardController@stats`

### Recent Transactions Data

**API Endpoint**: `/api/tenant/transactions/recent`

```json
{
    "data": [
        {
            "id": "TRX001",
            "customer": "John Doe",
            "total": 50000,
            "status": "completed",
            "created_at": "2024-01-15 10:30:00"
        }
    ]
}
```

### Best Sellers Data

**API Endpoint**: `/api/tenant/reports/products/best-selling`

**Existing Logic**: `app/Filament/Tenant/Widgets/TodaysBestSellingProduct.php`

---

## Files to Create

### Components
```
resources/views/components/
├── dashboard/
│   ├── stats-card.blade.php
│   ├── quick-actions.blade.php
│   ├── recent-transactions.blade.php
│   ├── best-sellers.blade.php
│   ├── low-stock.blade.php
│   └── expired-products.blade.php
└── settings/
    ├── sidebar.blade.php
    └── permission-matrix.blade.php
```

### Livewire Pages
```
resources/views/livewire/pages/
├── dashboard.blade.php
└── settings/
    ├── general.blade.php
    ├── users.blade.php
    ├── roles.blade.php
    ├── printer.blade.php
    ├── about.blade.php
    └── profile.blade.php
```

### Livewire Components
```
app/Livewire/
├── Pages/
│   ├── Dashboard.php
│   └── Settings/
│       ├── General.php
│       ├── Users.php
│       ├── Roles.php
│       ├── Printer.php
│       ├── About.php
│       └── Profile.php
└── Components/
    └── Dashboard/
        ├── StatsCard.php
        ├── RecentTransactions.php
        ├── BestSellers.php
        ├── LowStock.php
        └── ExpiredProducts.php
```

---

## Testing Checklist

### Unit Tests
- [ ] Test dashboard stats calculation
- [ ] Test settings save functionality
- [ ] Test role permission matrix

### Feature Tests
- [ ] Test dashboard page renders
- [ ] Test settings page renders
- [ ] Test settings update
- [ ] Test user CRUD
- [ ] Test role CRUD
- [ ] Test permission assignment

### Browser Tests
- [ ] Test dashboard widget loading
- [ ] Test settings form submission
- [ ] Test real-time stats update

---

## Acceptance Criteria

- [ ] Dashboard displays correct stats
- [ ] Stats cards show trends correctly
- [ ] Quick actions navigate to correct pages
- [ ] Recent transactions list displays
- [ ] Best sellers list displays
- [ ] Low stock alert shows items under threshold
- [ ] Expired products list displays
- [ ] Settings sidebar navigates between sections
- [ ] General settings form saves correctly
- [ ] User management CRUD works
- [ ] Role management CRUD works
- [ ] Permission matrix saves correctly
- [ ] Printer settings work
- [ ] About page displays version info
- [ ] Profile page saves user data
- [ ] All pages are responsive

---

## Notes

- Dashboard widgets should load data asynchronously
- Consider caching frequently accessed dashboard data
- Settings should be tenant-scoped (multi-tenancy)
- Profile changes should reflect immediately

---

## Next Phase

After completing this phase, proceed to [Phase 3: POS/Cashier (Mobile)](./PHASE_3.md)