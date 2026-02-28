# Phase 6: Category Management

**Duration**: 1-2 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation)

---

## Overview

Implement category CRUD (Create, Read, Update, Delete) functionality for product categorization.

---

## Figma Reference

| Screen | Node ID | URL |
|--------|---------|-----|
| Category List | 1492-2918 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2918) |

---

## Tasks

### 1. Category List Page

**File**: `resources/views/livewire/pages/categories/index.blade.php`

**Layout**:
```
┌─────────────────────────────────────┐
│  Categories           [+ Add New]   │
├─────────────────────────────────────┤
│  ┌─────────────────────────────┐   │
│  │ 🔍 Search categories...     │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Category Grid/List                 │
│  ┌─────────┐ ┌─────────┐           │
│  │ 📁 Food │ 📁 Drinks│           │
│  │ 45 items│ 23 items │           │
│  │ [Edit]  │ [Edit]   │           │
│  └─────────┘ └─────────┘           │
│  ┌─────────┐ ┌─────────┐           │
│  │ 📁 Snack│ 📁 Other │           │
│  │ 12 items│ 5 items  │           │
│  └─────────┘ └─────────┘           │
└─────────────────────────────────────┘
```

**Features**:
- Grid or list view toggle
- Category cards with item count
- Search functionality
- Sort alphabetically

**Tasks**:
- [ ] Create category list component
- [ ] Implement search filter
- [ ] Display product count per category
- [ ] Add edit/delete actions

### 2. Category Form (Modal)

**Create/Edit in Modal**:

```
┌─────────────────────────────────────┐
│  Add Category                    [×]│
├─────────────────────────────────────┤
│  Name *                             │
│  ┌─────────────────────────────┐   │
│  │ Category Name               │   │
│  └─────────────────────────────┘   │
│                                     │
│  Description                        │
│  ┌─────────────────────────────┐   │
│  │ Optional description...     │   │
│  └─────────────────────────────┘   │
│                                     │
│  Image (Optional)                   │
│  ┌─────────────────────────────┐   │
│  │    [Upload Image]           │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  [Cancel]              [Save]       │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create category form modal
- [ ] Add name validation
- [ ] Add image upload
- [ ] Handle create/update logic

### 3. Delete Confirmation

**Features**:
- Check if category has products
- Warn if products will be affected
- Option to move products to another category

**Tasks**:
- [ ] Create delete confirmation modal
- [ ] Check product dependency
- [ ] Show warning if category has products

---

## Routes

```php
Route::middleware('auth')->prefix('member')->group(function () {
    Route::get('/categories', \App\Livewire\Pages\Categories\Index::class)
        ->name('categories.index');
});
```

---

## Files to Create

```
app/Livewire/Pages/Categories/
└── Index.php

resources/views/livewire/pages/categories/
└── index.blade.php

resources/views/components/categories/
├── card.blade.php
└── form-modal.blade.php
```

---

## API Endpoints (Existing)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/tenant/master/categories` | GET | List categories |
| `/api/tenant/master/categories` | POST | Create category |
| `/api/tenant/master/categories/{id}` | PUT | Update category |
| `/api/tenant/master/categories/{id}` | DELETE | Delete category |

---

## Acceptance Criteria

- [ ] Category list displays all categories
- [ ] Product count shows correctly
- [ ] Create category works
- [ ] Update category works
- [ ] Delete category with confirmation
- [ ] Search filters categories
- [ ] Form validation works

---

## Next Phase

[Phase 7: Product Management](./PHASE_7.md)