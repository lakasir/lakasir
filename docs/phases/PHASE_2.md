# Phase 2: Menu Home & Settings UI

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation & Authentication)

---

## Overview

This phase implements the post-login Menu Home and settings pages. Based on Figma, the landing page after login is a tile-based menu (not a global sidebar dashboard).

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Menu Home Desktop | 369-1092 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=369-1092) |
| Menu Home Mobile | 1470-1204 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-1204) |
| Settings Menu | 1513-3250 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3250) |
| Simple Settings | 1513-3407 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3407) |

---

## Prerequisites

- [ ] Phase 1 complete
- [ ] All base UI components available
- [ ] Authentication working
- [ ] Shared header (logo + logout) available

---

## Tasks

### 1. Menu Home Page

**File**: `resources/views/livewire/pages/dashboard.blade.php`

**Volt Page**: `resources/views/livewire/pages/dashboard.blade.php`

#### 1.1 Menu Home Layout

**Design**: Responsive menu grid with action tiles (no global sidebar)

**Structure**:
```
┌────────────────────────────────────────────────────────────┐
│ Header: Logo (left) + Logout (right)                     │
├────────────────────────────────────────────────────────────┤
│ Menu Grid                                                 │
│ Desktop: 3 columns, 2 rows                               │
│ Mobile : 2 columns, 3 rows                               │
│                                                            │
│ [Transaction] [Product] [Member]                         │
│ [Profile]    [About]   [Setting]                         │
├────────────────────────────────────────────────────────────┤
│ Footer Link: Support Center                              │
└────────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create Menu Home page component
- [ ] Implement responsive grid (2 cols mobile, 3 cols desktop)
- [ ] Add header with logo and logout action
- [ ] Add Support Center link placement per Figma
- [ ] Match spacing, tile sizing, and color accents from Figma

#### 1.2 Menu Tile Component

**File**: `resources/views/components/dashboard/menu-tile.blade.php`

**Props**:
- `title`: tile label
- `route`: target route
- `icon`: optional icon
- `accent`: tile accent color class/token
- `disabled`: boolean (permission-aware)

**Menu Items**:
1. **Transaction**
2. **Product**
3. **Member**
4. **Profile**
5. **About**
6. **Setting**

**Tasks**:
- [ ] Create reusable tile component for desktop/mobile
- [ ] Add icon + label alignment matching Figma
- [ ] Add hover/focus/active states
- [ ] Handle disabled state when route is not permitted

#### 1.3 Navigation Mapping & Access Control

**Route Mapping**:
- Transaction → transaction module route
- Product → product module route
- Member → member module route
- Profile → profile settings route
- About → about settings route
- Setting → general settings route

**Tasks**:
- [ ] Map all six tiles to existing member-prefixed routes
- [ ] Apply permission checks per tile visibility/state
- [ ] Add navigation loading feedback (optional)

#### 1.4 Logout & Support Actions

**Features**:
- Logout action in top-right header
- Support Center link at bottom area

**Tasks**:
- [ ] Use POST logout flow from authenticated session controller
- [ ] Ensure Support Center link is visible on both breakpoints
- [ ] Add localization keys for Logout and Support Center labels

---

### 2. Settings Pages

**File**: `resources/views/livewire/pages/settings/general.blade.php`

#### 2.1 Settings Navigation

**Design Reference**: [Figma - Settings Menu](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1513-3250)

**Note**: The sidebar in this section is scoped to settings pages only, not global application navigation.

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
use Livewire\Volt\Volt;

Route::middleware('auth')->prefix('member')->group(function () {
    Volt::route('/dashboard', 'pages/dashboard')->name('dashboard');
    
    Route::prefix('settings')->group(function () {
        Volt::route('/', 'pages/settings/general')->name('settings.general');
        Volt::route('/users', 'pages/settings/users')->name('settings.users');
        Volt::route('/roles', 'pages/settings/roles')->name('settings.roles');
        Volt::route('/printer', 'pages/settings/printer')->name('settings.printer');
        Volt::route('/about', 'pages/settings/about')->name('settings.about');
        Volt::route('/profile', 'pages/settings/profile')->name('settings.profile');
    });
});
```

---

## Menu Home Data Requirements

### Menu Configuration

Menu Home can use static configuration for the initial slicing:

```php
[
    ['key' => 'transaction', 'label' => 'menu.transaction', 'route' => '...'],
    ['key' => 'product', 'label' => 'menu.product', 'route' => '...'],
    ['key' => 'member', 'label' => 'menu.member', 'route' => '...'],
    ['key' => 'profile', 'label' => 'menu.profile', 'route' => 'settings.profile'],
    ['key' => 'about', 'label' => 'menu.about', 'route' => 'settings.about'],
    ['key' => 'setting', 'label' => 'menu.setting', 'route' => 'settings.general'],
]
```

### Endpoint Dependencies

- Logout: `POST /logout`
- Optional support center URL: tenant/general setting value

### Future Enhancement (Optional)

- Add badge counters per tile (pending transactions, low stock, etc.) from lightweight APIs

---

## Files to Create

### Components
```
resources/views/components/
├── dashboard/
│   ├── menu-tile.blade.php
│   └── menu-grid.blade.php
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

### Optional Class Components (Only If Needed)
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
```

---

## Testing Checklist

### Unit Tests
- [ ] Test menu tile visibility by permission
- [ ] Test settings save functionality
- [ ] Test role permission matrix

### Feature Tests
- [ ] Test Menu Home page renders
- [ ] Test all menu tile routes are reachable
- [ ] Test logout action from Menu Home
- [ ] Test settings page renders
- [ ] Test settings update
- [ ] Test user CRUD
- [ ] Test role CRUD
- [ ] Test permission assignment

### Browser Tests
- [ ] Test Menu Home grid layout (mobile and desktop)
- [ ] Test settings form submission
- [ ] Test logout flow from header

---

## Acceptance Criteria

- [ ] Menu Home displays 6 tiles matching Figma labels
- [ ] Desktop and mobile tile layouts match Figma structure
- [ ] Each tile navigates to the correct module route
- [ ] Logout action works from Menu Home header
- [ ] Support Center link is visible and clickable
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

- Menu Home is a navigation entry page, not a KPI widget dashboard
- Keep settings sidebar local to settings pages only
- Settings should be tenant-scoped (multi-tenancy)
- Profile changes should reflect immediately

---

## Next Phase

After completing this phase, proceed to [Phase 3: POS/Cashier (Mobile)](./PHASE_3.md)