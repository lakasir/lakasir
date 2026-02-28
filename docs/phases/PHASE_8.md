# Phase 8: Member & User Management

**Duration**: 2-3 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation)

---

## Overview

Implement member (customer) management and user (staff) management with role-based access control.

---

## Tasks

### 1. Member Management

#### 1.1 Member List

**File**: `resources/views/livewire/pages/members/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Members                              [+ Add Member]     │
├─────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────┐   │
│  │ 🔍 Search by name, phone, or email...           │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Name      │ Phone        │ Points │ Total Spent │ Acts│
│  ────────────────────────────────────────────────────── │
│  John Doe  │ 08123456789  │ 150    │ Rp 1.5M    │ ⋮   │
│  Jane S.   │ 08234567890  │ 75     │ Rp 750K    │ ⋮   │
├─────────────────────────────────────────────────────────┤
│  Showing 1-25 of 50                     [<] 1 2 [>]    │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create member list component
- [ ] Implement search filter
- [ ] Add pagination
- [ ] Show member stats

#### 1.2 Member Form

**Fields**:
- Name *
- Phone * (unique)
- Email (optional)
- Address (optional)
- Date of birth (optional)
- Points (read-only)

**Tasks**:
- [ ] Create member form modal
- [ ] Add form validation
- [ ] Handle duplicate phone check

#### 1.3 Member Detail

**Features**:
- Member info card
- Transaction history
- Points history
- Total spent statistics

**Tasks**:
- [ ] Create member detail page
- [ ] Show transaction list
- [ ] Display points history

---

### 2. User Management

#### 2.1 User List

**File**: `resources/views/livewire/pages/users/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Users                                 [+ Add User]      │
├─────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────┐   │
│  │ 🔍 Search by name or email...                   │   │
│  └─────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────┤
│  Avatar │ Name     │ Email        │ Role   │ Status   │
│  ────────────────────────────────────────────────────── │
│  [img]  │ John     │ john@ex.com  │ Admin  │ ●Active  │
│  [img]  │ Jane     │ jane@ex.com  │ Cashier│ ●Active  │
│  [img]  │ Bob      │ bob@ex.com   │ Cashier│ ○Inactive│
├─────────────────────────────────────────────────────────┤
│  Showing 1-10 of 10                                    │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create user list component
- [ ] Implement status toggle
- [ ] Add search functionality

#### 2.2 User Form

**Fields**:
- Name *
- Email * (unique)
- Password (required for new, optional for edit)
- Password confirmation
- Role * (dropdown)
- Avatar upload (optional)
- Status (active/inactive toggle)

**Tasks**:
- [ ] Create user form
- [ ] Add role dropdown
- [ ] Implement password validation
- [ ] Handle avatar upload

---

### 3. Role Management

#### 3.1 Role List

**File**: `resources/views/livewire/pages/roles/index.blade.php`

**Default Roles**:
- Super Admin (full access)
- Admin (management access)
- Cashier (POS access)
- Inventory (stock management)
- Viewer (read-only)

**Tasks**:
- [ ] Create role list component
- [ ] Show permission count per role
- [ ] Add create/edit functionality

#### 3.2 Role Form with Permission Matrix

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Role Name: [____________]                              │
├─────────────────────────────────────────────────────────┤
│  Permissions                                            │
│  ┌──────────────────────────────────────────────────┐  │
│  │           │ View │ Create │ Update │ Delete      │  │
│  │ ──────────────────────────────────────────────── │  │
│  │ Products  │  ✓   │   ✓    │   ✓    │   ✓   │  │
│  │ Category  │  ✓   │   ✓    │   ✓    │   ✓   │  │
│  │ Members   │  ✓   │   ✓    │   ✓    │       │  │
│  │ Users     │  ✓   │        │        │       │  │
│  │ Roles     │  ✓   │        │        │       │  │
│  │ Reports   │  ✓   │        │        │       │  │
│  │ Settings  │  ✓   │        │        │       │  │
│  │ ──────────────────────────────────────────────── │  │
│  │ POS                 │  ✓   │        │        │  │
│  │ Transactions        │  ✓   │        │        │  │
│  │ Purchasing          │  ✓   │   ✓    │        │  │
│  │ Stock Opname        │  ✓   │   ✓    │        │  │
│  └──────────────────────────────────────────────────┘  │
├─────────────────────────────────────────────────────────┤
│  [Cancel]                                 [Save]        │
└─────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create role form
- [ ] Build permission matrix
- [ ] Group permissions by module
- [ ] Save role with permissions

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    // Members
    Route::get('/members', \App\Livewire\Pages\Members\Index::class)
        ->name('members.index');
    Route::get('/members/{id}', \App\Livewire\Pages\Members\Show::class)
        ->name('members.show');
    
    // Users
    Route::get('/users', \App\Livewire\Pages\Users\Index::class)
        ->name('users.index');
    
    // Roles
    Route::get('/roles', \App\Livewire\Pages\Roles\Index::class)
        ->name('roles.index');
});
```

---

## Files to Create

```
app/Livewire/Pages/
├── Members/
│   ├── Index.php
│   └── Show.php
├── Users/
│   └── Index.php
└── Roles/
    └── Index.php

resources/views/livewire/pages/
├── members/
│   ├── index.blade.php
│   └── show.blade.php
├── users/
│   └── index.blade.php
└── roles/
    └── index.blade.php

resources/views/components/
├── members/
│   ├── card.blade.php
│   └── transaction-list.blade.php
├── users/
│   └── status-badge.blade.php
└── roles/
    └── permission-matrix.blade.php
```

---

## API Endpoints (Existing)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/tenant/master/members` | GET/POST | List/Create members |
| `/api/tenant/master/members/{id}` | GET/PUT/DELETE | Member CRUD |
| `/api/tenant/users` | GET/POST | List/Create users |
| `/api/tenant/users/{id}` | GET/PUT/DELETE | User CRUD |
| `/api/tenant/roles` | GET/POST | List/Create roles |
| `/api/tenant/roles/{id}` | GET/PUT/DELETE | Role CRUD |

---

## Acceptance Criteria

### Members
- [ ] Member list displays correctly
- [ ] Search finds members
- [ ] Create member works
- [ ] Edit member works
- [ ] Member detail shows transactions
- [ ] Points display correctly

### Users
- [ ] User list displays correctly
- [ ] Create user with role works
- [ ] Edit user works
- [ ] Status toggle works
- [ ] Password change works

### Roles
- [ ] Role list displays
- [ ] Permission matrix renders correctly
- [ ] Create role with permissions works
- [ ] Edit role permissions works
- [ ] Role assignment to user works

---

## Next Phase

[Phase 9: Purchasing & Stock Opname](./PHASE_9.md)