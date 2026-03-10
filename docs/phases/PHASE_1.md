# Phase 1: Foundation & Authentication

**Duration**: 2-3 days  
**Status**: Done (UI Slicing Scope)  
**Completed On**: 2026-03-10  
**Dependencies**: None

---

## Overview

This phase establishes the foundational UI components and authentication system. All subsequent phases will build upon these base components.

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Login Mobile | 1470-836 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-836&m=dev) |
| Login Tablet | 285-181 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=285-181&m=dev) |
| Sign Up Mobile | 1470-1036 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-1036&m=dev) |
| Sign Up Tablet | 283-181 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=283-181&m=dev) |

---

## Prerequisites

Before starting, ensure:
- [ ] Local development environment is running
- [ ] Database is migrated and seeded
- [ ] npm dependencies installed (`npm install`)
- [ ] Vite is running (`npm run dev`)

---

## Tasks

### 1. Base Layout Components

#### 1.1 App Layout (`resources/views/components/layouts/app.blade.php`)

```blade
{{-- Main authenticated application layout --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar + Main Content wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('components.shared.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('components.shared.header')
            
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @persist('toasts')
        <livewire:toast-container />
    @endpersist
</body>
</html>
```

**Implementation Steps**:
- [ ] Create file structure
- [ ] Implement responsive sidebar (collapsible on mobile)
- [ ] Add dark mode support
- [ ] Include toast notification container
- [ ] Add mobile navigation drawer

#### 1.2 Guest Layout (`resources/views/components/layouts/guest.blade.php`)

For public pages (landing, registration, etc.)

**Implementation Steps**:
- [ ] Create minimal layout without sidebar
- [ ] Include only header with logo
- [ ] Footer with links

#### 1.3 Auth Layout (`resources/views/components/layouts/auth.blade.php`)

For authentication pages (login, password reset).

**Implementation Steps**:
- [ ] Centered card design
- [ ] Responsive for mobile
- [ ] Logo and branding
- [ ] Optional background pattern

---

### 2. UI Component Library

Create reusable components in `resources/views/components/ui/`

#### 2.1 Button Component

**File**: `resources/views/components/ui/button.blade.php`

**Props**:
- `variant`: primary, secondary, danger, ghost
- `size`: sm, md, lg
- `disabled`: boolean
- `loading`: boolean (shows spinner)
- `icon`: optional icon name

**Usage**:
```blade
<x-button variant="primary" size="md">
    Save Changes
</x-button>

<x-button variant="danger" loading>
    Deleting...
</x-button>
```

**Tasks**:
- [ ] Create button variants
- [ ] Add loading state with spinner
- [ ] Add icon support
- [ ] Implement disabled state styling
- [ ] Add hover/focus states

#### 2.2 Input Component

**File**: `resources/views/components/ui/input.blade.php`

**Props**:
- `type`: text, password, email, number, etc.
- `label`: optional label text
- `error`: error message
- `hint`: optional help text
- `icon`: optional leading icon
- `disabled`: boolean

**Usage**:
```blade
<x-input 
    name="email" 
    type="email" 
    label="Email Address"
    placeholder="you@example.com"
    :error="$errors->first('email')"
/>
```

**Tasks**:
- [ ] Create input wrapper with label
- [ ] Add error state styling
- [ ] Add icon support (leading/trailing)
- [ ] Implement disabled state
- [ ] Add focus ring

#### 2.3 Select Component

**File**: `resources/views/components/ui/select.blade.php`

**Props**:
- `options`: array of options
- `placeholder`: optional placeholder
- `multiple`: boolean for multi-select
- `searchable`: boolean for searchable dropdown
- `disabled`: boolean

**Tasks**:
- [ ] Basic select styling
- [ ] Add searchable functionality (Alpine.js)
- [ ] Multi-select with checkboxes
- [ ] Add clear button

#### 2.4 Modal Component

**File**: `resources/views/components/ui/modal.blade.php`

**Props**:
- `title`: modal title
- `size`: sm, md, lg, xl, full
- `position`: center, bottom (for mobile)
- `closeable`: boolean

**Usage**:
```blade
<x-modal name="confirm-delete" title="Confirm Deletion">
    <p>Are you sure you want to delete this item?</p>
    
    <x-slot:actions>
        <x-button variant="ghost" @click="$dispatch('close')">
            Cancel
        </x-button>
        <x-button variant="danger">
            Delete
        </x-button>
    </x-slot:actions>
</x-modal>
```

**Tasks**:
- [ ] Create backdrop overlay
- [ ] Add close on backdrop click
- [ ] Add close on escape key
- [ ] Implement slide-up animation (mobile)
- [ ] Implement fade animation (desktop)
- [ ] Add body scroll lock

#### 2.5 Card Component

**File**: `resources/views/components/ui/card.blade.php`

**Props**:
- `title`: optional card title
- `subtitle`: optional card subtitle
- `padding`: none, sm, md, lg
- `shadow`: boolean

**Tasks**:
- [ ] Create card wrapper
- [ ] Add header slot
- [ ] Add footer slot
- [ ] Implement hover effects (if clickable)

#### 2.6 Table Component

**File**: `resources/views/components/ui/table.blade.php`

**Props**:
- `columns`: array of column definitions
- `data`: array of row data
- `striped`: boolean
- `hoverable`: boolean
- `sortable`: boolean

**Usage**:
```blade
<x-table :columns="['Name', 'Email', 'Actions']" :data="$users">
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <x-button variant="ghost" size="sm">Edit</x-button>
            </td>
        </tr>
    @endforeach
</x-table>
```

**Tasks**:
- [ ] Create table wrapper with responsive scroll
- [ ] Add sorting functionality
- [ ] Add pagination integration
- [ ] Implement row selection

#### 2.7 Dropdown Component

**File**: `resources/views/components/ui/dropdown.blade.php`

**Props**:
- `trigger`: button or custom trigger
- `position`: bottom-start, bottom-end, etc.
- `width`: sm, md, lg

**Tasks**:
- [ ] Create dropdown trigger
- [ ] Implement dropdown menu
- [ ] Add click-outside detection
- [ ] Add keyboard navigation

#### 2.8 Toast/Notification Component

**File**: `resources/views/components/ui/toast.blade.php`

**Types**: success, error, warning, info

**Tasks**:
- [ ] Create toast container
- [ ] Implement toast animations
- [ ] Add auto-dismiss
- [ ] Add manual dismiss button
- [ ] Support stacking multiple toasts

#### 2.9 Badge Component

**File**: `resources/views/components/ui/badge.blade.php`

**Props**:
- `variant`: default, primary, success, danger, warning
- `size`: sm, md

#### 2.10 Avatar Component

**File**: `resources/views/components/ui/avatar.blade.php`

**Props**:
- `src`: image URL
- `name`: for initials fallback
- `size`: sm, md, lg, xl

#### 2.11 Skeleton Component

**File**: `resources/views/components/ui/skeleton.blade.php`

For loading states:
- [ ] Create skeleton pulse animation
- [ ] Add common skeleton patterns (text, image, card)

#### 2.12 Pagination Component

**File**: `resources/views/components/ui/pagination.blade.php`

**Tasks**:
- [ ] Create pagination wrapper
- [ ] Add first/last page buttons
- [ ] Add page number display
- [ ] Add per-page selector

---

### 3. Navigation Components

#### 3.1 Sidebar (`resources/views/components/shared/sidebar.blade.php`)

**Features**:
- Collapsible on desktop
- Drawer on mobile
- Active state indication
- Nested menu support
- Role-based menu visibility

**Menu Structure**:
```
Dashboard
POS
├── Cashier
├── Transactions
└── History
Master
├── Products
├── Categories
├── Members
├── Suppliers
└── Payment Methods
Inventory
├── Stock Opname
└── Purchasing
Finance
├── Receivables
└── Vouchers
Reports
├── Sales
├── Products
├── Cashiers
└── Purchases
Settings
├── General
├── Users
├── Roles
└── Printer
```

**Tasks**:
- [ ] Create sidebar HTML structure
- [ ] Implement collapse/expand
- [ ] Add mobile drawer
- [ ] Add active route detection
- [ ] Implement role-based visibility

#### 3.2 Header (`resources/views/components/shared/header.blade.php`)

**Features**:
- Hamburger menu (mobile)
- Search bar (desktop)
- User dropdown
- Notifications bell
- Theme toggle

**Tasks**:
- [ ] Create header structure
- [ ] Add mobile menu toggle
- [ ] Add user menu dropdown
- [ ] Implement search functionality
- [ ] Add dark mode toggle

#### 3.3 Mobile Nav (`resources/views/components/shared/mobile-nav.blade.php`)

Bottom navigation for mobile devices.

**Features**:
- Home
- POS
- History
- Profile

---

### 4. Authentication Pages

#### 4.1 Login Page

**File**: `resources/views/livewire/pages/auth/login.blade.php`

**Volt Page**: `resources/views/livewire/pages/auth/login.blade.php`

**Design Reference**: [Figma - Login](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1470-836)

**Features**:
- Email/username input
- Password input with show/hide
- Remember me checkbox
- Forgot password link
- Login button with loading state
- Error messages display
- Logo and branding

**Component State (Volt)**:
```php
state([
    'email' => '',
    'password' => '',
    'remember' => true,
]);

$login = function () {
    // Validate and authenticate
};
```

**Tasks**:
- [ ] Create Volt page component
- [ ] Design login form UI matching Figma
- [ ] Implement form validation
- [ ] Add authentication logic
- [ ] Handle error states
- [ ] Add loading state during login
- [ ] Redirect after successful login
- [ ] Add multi-tenancy support (tenant detection)

#### 4.2 Logout

**Tasks**:
- [ ] Add logout method to user dropdown
- [ ] Clear session data
- [ ] Redirect to login page

#### 4.3 Password Reset (if needed)

**Tasks**:
- [ ] Create forgot password form
- [ ] Create reset password form
- [ ] Integrate with Laravel's password reset

#### 4.4 Session Management

**Tasks**:
- [ ] Implement "remember me" functionality
- [ ] Add session timeout handling
- [ ] Handle concurrent sessions

---

### 5. Form Components

#### 5.1 Form Wrapper

**File**: `resources/views/components/form/wrapper.blade.php`

#### 5.2 Form Section

**File**: `resources/views/components/form/section.blade.php`

For grouping related form fields.

#### 5.3 Form Actions

**File**: `resources/views/components/form/actions.blade.php`

Submit/Cancel buttons wrapper.

---

### 6. Utility Components

#### 6.1 Empty State

**File**: `resources/views/components/ui/empty-state.blade.php`

**Props**:
- `title`: main message
- `description`: optional description
- `icon`: optional icon
- `action`: optional action button

#### 6.2 Loading Spinner

**File**: `resources/views/components/ui/spinner.blade.php`

**Props**:
- `size`: sm, md, lg
- `color`: primary, white

#### 6.3 Confirm Dialog

**File**: `resources/views/components/ui/confirm-dialog.blade.php`

**Props**:
- `title`: dialog title
- `message`: confirmation message
- `confirmText`: confirm button text
- `cancelText`: cancel button text
- `variant`: danger, warning, primary

---

## Multilanguage Requirements

**IMPORTANT**: All text in UI components must be translatable using Laravel's localization system.

### Translation Keys Required
- Use `__('key')` helper for all user-facing text
- Add translations to `lang/en/`, `lang/id/`, `lang/es/`
- Use descriptive key names (e.g., `auth.welcome_back`)

### Example
```blade
{{-- Instead of --}}
<h1>Welcome Back</h1>

{{-- Use --}}
<h1>{{ __('auth.welcome_back') }}</h1>
```

---

## Routes

Add authentication routes in `routes/web.php`:

```php
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages/auth/login')->name('login');
    Volt::route('/auth/register', 'pages/auth/register')->name('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
```

---

## Files to Create

### Components
```
resources/views/components/
├── layouts/
│   ├── app.blade.php
│   ├── guest.blade.php
│   └── auth.blade.php
├── ui/
│   ├── button.blade.php
│   ├── input.blade.php
│   ├── select.blade.php
│   ├── modal.blade.php
│   ├── card.blade.php
│   ├── table.blade.php
│   ├── dropdown.blade.php
│   ├── toast.blade.php
│   ├── badge.blade.php
│   ├── avatar.blade.php
│   ├── skeleton.blade.php
│   ├── pagination.blade.php
│   ├── spinner.blade.php
│   ├── empty-state.blade.php
│   └── confirm-dialog.blade.php
├── shared/
│   ├── sidebar.blade.php
│   ├── header.blade.php
│   └── mobile-nav.blade.php
└── form/
    ├── wrapper.blade.php
    ├── section.blade.php
    └── actions.blade.php
```

### Volt Pages
```
resources/views/livewire/pages/auth/
├── login.blade.php
└── register.blade.php
```

### Livewire Class Components (Only If Needed)
```
app/Livewire/
└── Components/
    └── ToastContainer.php
```

---

## Testing Checklist

### Unit Tests
- [ ] Test user authentication
- [ ] Test validation rules
- [ ] Test session management

### Feature Tests
- [ ] Test login page renders
- [ ] Test successful login flow
- [ ] Test failed login (wrong credentials)
- [ ] Test logout flow
- [ ] Test unauthenticated redirect

### Browser Tests
- [ ] Test login form submission
- [ ] Test sidebar navigation
- [ ] Test responsive layouts

---

## Acceptance Criteria

- [ ] User can log in with email/password
- [ ] Sidebar displays all menu items based on role
- [ ] Sidebar collapses on mobile, shows drawer
- [ ] All base UI components render correctly
- [ ] Toast notifications appear and dismiss
- [ ] Modal opens and closes correctly
- [ ] Form validation errors display inline
- [ ] Loading states show spinners
- [ ] Application is responsive (mobile, tablet, desktop)
- [ ] Dark mode toggle works correctly

---

## Notes

- Keep all Filament resources intact during this phase
- New routes should use `/member/` prefix (check existing route structure)
- Multi-tenancy middleware should still work
- Test with different user roles

---

## Completion Notes

- Auth UI slicing is completed for this phase scope (login and sign up).
- Pages are aligned to the Figma nodes listed above for mobile and tablet.
- Auth flow logic remains integrated with tenancy registration and existing routes.
- Translation keys for auth screens are available in EN, ID, and ES.

---

## Next Phase

After completing this phase, proceed to [Phase 2: Dashboard & Common UI](./PHASE_2.md)
