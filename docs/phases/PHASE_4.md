# Phase 4: POS/Cashier (Tablet)

**Duration**: 3-4 days  
**Status**: Not Started  
**Dependencies**: Phase 3 (POS Mobile)

---

## Overview

This phase adapts the mobile POS for tablet devices (768px+). The tablet version features a split-screen layout with products on the left and cart always visible on the right.

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Product List + Cart Empty | 96-572 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=96-572) |
| Add to Cart Popup Tablet | 175-876 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-876) |
| Product List + Cart Items | 175-517 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-517) |
| Cart Modal | 225-1015 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=225-1015) |

---

## Prerequisites

- [ ] Phase 3 complete
- [ ] Mobile POS fully functional
- [ ] All shared components available

---

## Design Differences from Mobile

| Feature | Mobile | Tablet |
|---------|--------|--------|
| Layout | Full-screen (switch views) | Split-screen (products + cart) |
| Cart Display | Bottom bar + full page | Always visible sidebar |
| Product Modal | Full-screen slide-up | Centered modal |
| Payment Modal | Full-screen | Centered modal |
| Grid Columns | 2 | 3-4 |

---

## Tasks

### 1. Split-Screen Layout

**File**: `resources/views/livewire/pos/tablet.blade.php`

#### 1.1 Layout Structure

```
┌────────────────────────────────────────────────────────────────────┐
│  Header: [🔍 Search] [Categories ▼]         [User] [🔔]           │
├─────────────────────────────────────────┬──────────────────────────┤
│                                         │                          │
│  Products Section (60-65%)              │  Cart Section (35-40%)  │
│                                         │                          │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐       │  ┌────────────────────┐  │
│  │     │ │     │ │     │ │     │       │  │ Cart (3 items)     │  │
│  │     │ │     │ │     │ │     │       │  │                    │  │
│  └─────┘ └─────┘ └─────┘ └─────┘       │  │ Item 1             │  │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐       │  │ x2  Rp 50.000      │  │
│  │     │ │     │ │     │ │     │       │  │                    │  │
│  │     │ │     │ │     │ │     │       │  │ Item 2             │  │
│  └─────┘ └─────┘ └─────┘ └─────┘       │  │ x1  Rp 25.000      │  │
│                                         │  │                    │  │
│  [Load More]                            │  │ ...                │  │
│                                         │  │                    │  │
│                                         │  │ ──────────────────│  │
│                                         │  │ Subtotal: Rp 75.000│  │
│                                         │  │                    │  │
│                                         │  │ [Customer ▼]       │  │
│                                         │  │                    │  │
│                                         │  │ [Payment →]        │  │
│                                         │  └────────────────────┘  │
└─────────────────────────────────────────┴──────────────────────────┘
```

**Tasks**:
- [ ] Create tablet layout component
- [ ] Implement responsive split-screen
- [ ] Add resizable panels (optional)
- [ ] Handle breakpoint switching (mobile ↔ tablet)

#### 1.2 Responsive Breakpoints

```css
/* Mobile First */
.pos-container { /* single column */ }

/* Tablet (md: 768px) */
@media (min-width: 768px) {
    .pos-container {
        display: grid;
        grid-template-columns: 1fr;
    }
}

/* Tablet Landscape (lg: 1024px) */
@media (min-width: 1024px) {
    .pos-container {
        grid-template-columns: 1.5fr 1fr;
    }
}

/* Desktop (xl: 1280px) */
@media (min-width: 1280px) {
    .pos-container {
        grid-template-columns: 2fr 1fr;
    }
}
```

---

### 2. Product Section (Left Panel)

#### 2.1 Product Grid

**Layout**: 3-4 columns on tablet

**Tasks**:
- [ ] Adjust grid columns for tablet
- [ ] Add larger product cards
- [ ] Implement infinite scroll
- [ ] Add scroll position memory

#### 2.2 Search & Filter Bar

**Position**: Top of product section

**Features**:
- Search input
- Category dropdown (instead of chips)
- Clear filters button

**Tasks**:
- [ ] Create compact search bar
- [ ] Add category dropdown
- [ ] Implement search with debounce

#### 2.3 Product Card (Tablet Variant)

**Features**:
- Larger size
- More visible stock indicator
- Hover effect
- Quick-add button (optional)

**Tasks**:
- [ ] Create tablet product card
- [ ] Add hover states
- [ ] Implement quick-add to cart

---

### 3. Cart Section (Right Panel)

**Design Reference**: [Cart Items State](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-517)

#### 3.1 Cart Sidebar Layout

```
┌──────────────────────────┐
│  🛒 Cart (3 items)       │
│  [Clear All]             │
├──────────────────────────┤
│  ┌────────────────────┐  │
│  │ [Img] Product 1    │  │
│  │       [−] 2 [+]    │  │
│  │       Rp 50.000    │  │
│  │       [🗑️] [✏️]    │  │
│  └────────────────────┘  │
│  ┌────────────────────┐  │
│  │ [Img] Product 2    │  │
│  │       [−] 1 [+]    │  │
│  │       Rp 25.000    │  │
│  └────────────────────┘  │
│                          │
│  (scrollable area)       │
│                          │
├──────────────────────────┤
│  ┌────────────────────┐  │
│  │ 📝 Add Note        │  │
│  └────────────────────┘  │
├──────────────────────────┤
│  Subtotal: Rp 75.000     │
│  Discount: Rp 0          │
│  ─────────────────────   │
│  TOTAL: Rp 75.000        │
├──────────────────────────┤
│  Customer:               │
│  [Select Customer ▼]     │
├──────────────────────────┤
│  [Proceed to Payment]    │
└──────────────────────────┘
```

**Tasks**:
- [ ] Create cart sidebar component
- [ ] Add scrollable cart items
- [ ] Show sticky summary at bottom
- [ ] Add customer selector
- [ ] Add payment button

#### 3.2 Cart Item Row (Tablet)

**Features**:
- Product thumbnail (larger)
- Name and unit
- Quantity controls
- Line total
- Edit and delete buttons

**Tasks**:
- [ ] Create tablet cart item component
- [ ] Add inline quantity controls
- [ ] Add edit/delete buttons
- [ ] Add hover states

#### 3.3 Empty Cart State

**Design Reference**: [Cart Empty](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=96-572)

```
┌──────────────────────────┐
│                          │
│                          │
│       🛒                 │
│                          │
│   Your cart is empty     │
│                          │
│   Tap products to add    │
│   them to your cart      │
│                          │
│                          │
└──────────────────────────┘
```

**Tasks**:
- [ ] Create empty cart illustration
- [ ] Add helpful message
- [ ] Add call-to-action (optional)

---

### 4. Product Modal (Tablet)

**Design Reference**: [Add to Cart Tablet](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=175-876)

#### 4.1 Modal Layout

**Position**: Centered overlay (not full-screen)

```
┌──────────────────────────────────────────────────────────────┐
│                            (backdrop)                         │
│        ┌────────────────────────────────────────┐            │
│        │  [×]                                   │            │
│        │                                        │            │
│        │  ┌──────┐ Product Name                 │            │
│        │  │      │ Category                     │            │
│        │  │ IMG  │ Rp 25.000                    │            │
│        │  │      │ Stock: 50                    │            │
│        │  └──────┘                              │            │
│        │                                        │            │
│        │  Select Unit:                          │            │
│        │  ○ Regular (Rp 25.000)                 │            │
│        │  ○ Large (Rp 35.000)                   │            │
│        │                                        │            │
│        │  Quantity:                             │            │
│        │  [−]  1  [+]                           │            │
│        │                                        │            │
│        │  Notes: [________________________]     │            │
│        │                                        │            │
│        │  Subtotal: Rp 25.000                   │            │
│        │                                        │            │
│        │  [Cancel]           [Add to Cart]      │            │
│        └────────────────────────────────────────┘            │
└──────────────────────────────────────────────────────────────┘
```

**Features**:
- Centered position
- Close button (×)
- Click outside to close
- Escape key to close
- Fade + scale animation

**Tasks**:
- [ ] Create tablet product modal
- [ ] Add backdrop overlay
- [ ] Implement close handlers
- [ ] Add smooth animations
- [ ] Handle keyboard navigation

---

### 5. Payment Modal (Tablet)

**Design Reference**: [Cart Modal](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=225-1015)

#### 5.1 Payment Modal Layout

```
┌──────────────────────────────────────────────────────────────┐
│                            (backdrop)                         │
│        ┌────────────────────────────────────────┐            │
│        │  Payment                            [×]│            │
│        ├────────────────────────────────────────┤            │
│        │  Order Summary                         │            │
│        │  ─────────────────────────────────     │            │
│        │  Item 1 x2                Rp 50.000    │            │
│        │  Item 2 x1                Rp 25.000    │            │
│        │  ─────────────────────────────────     │            │
│        │  Total                    Rp 75.000    │            │
│        ├────────────────────────────────────────┤            │
│        │  Payment Method:                        │            │
│        │  ┌────────┐ ┌────────┐ ┌────────┐      │            │
│        │  │  Cash  │ │  QRIS  │ │  Card  │      │            │
│        │  └────────┘ └────────┘ └────────┘      │            │
│        ├────────────────────────────────────────┤            │
│        │  [Payment specific fields]              │            │
│        │                                         │            │
│        ├────────────────────────────────────────┤            │
│        │  [Cancel]              [Confirm Payment]│            │
│        └────────────────────────────────────────┘            │
└──────────────────────────────────────────────────────────────┘
```

**Tasks**:
- [ ] Create payment modal component
- [ ] Add payment method selection
- [ ] Handle payment flow within modal
- [ ] Show success screen in modal
- [ ] Add print option

#### 5.2 Payment Success in Modal

```
┌──────────────────────────────────────────────────────────────┐
│                            (backdrop)                         │
│        ┌────────────────────────────────────────┐            │
│        │                                         │            │
│        │              ✅ Success!                │            │
│        │                                         │            │
│        │     Transaction ID: TRX-2024-001234    │            │
│        │     Total: Rp 75.000                   │            │
│        │     Paid: Rp 100.000                   │            │
│        │     Change: Rp 25.000                  │            │
│        │                                         │            │
│        │     [Print Receipt] [New Transaction]  │            │
│        │                                         │            │
│        └────────────────────────────────────────┘            │
└──────────────────────────────────────────────────────────────┘
```

---

### 6. Responsive Switching

**File**: `resources/views/livewire/pos/main.blade.php`

#### 6.1 Adaptive Component

The main POS component should detect screen size and switch between mobile and tablet layouts.

```php
// In Volt component script section
public bool $isTablet = false;

public function mount()
{
    $this->isTablet = request()->wantsJson() ? false : true;
}

#[On('window-resize')]
public function updateLayout($width)
{
    $this->isTablet = $width >= 768;
}
```

**Tasks**:
- [ ] Detect initial screen size
- [ ] Listen for resize events
- [ ] Switch layouts dynamically
- [ ] Preserve state across switches

#### 6.2 View Selection

```blade
{{-- In main.blade.php --}}
<div>
    @if($isTablet)
        @include('livewire.pos.partials.tablet-layout')
    @else
        @include('livewire.pos.partials.mobile-layout')
    @endif
</div>
```

---

## Shared State

The same Volt component handles both mobile and tablet views. State is preserved across view switches:

```php
// Shared state (from Phase 3)
public array $cart = [];
public $products;
public $categories;
public string $search = '';
public ?int $selectedCategory = null;
// ...
```

---

## Routes

Same routes as mobile POS (Phase 3) - responsive detection handles layout.

```php
use Livewire\Volt\Volt;

Route::middleware('auth')->prefix('member')->group(function () {
    Volt::route('/pos', 'pos/main')->name('pos.index');
});
```

---

## Files to Create

### Views
```
resources/views/livewire/pos/
├── main.blade.php (responsive container)
├── partials/
│   ├── mobile-layout.blade.php
│   ├── tablet-layout.blade.php
│   └── product-modal.blade.php
└── components/
    ├── tablet-product-card.blade.php
    ├── tablet-cart-item.blade.php
    └── tablet-cart-sidebar.blade.php
```

### CSS (Tailwind)
```css
/* resources/css/pos.css */
.pos-product-grid {
    @apply grid grid-cols-2 gap-3 p-4;
}

@media (min-width: 768px) {
    .pos-product-grid {
        @apply grid-cols-3 gap-4;
    }
}

@media (min-width: 1024px) {
    .pos-product-grid {
        @apply grid-cols-4 gap-4;
    }
}
```

---

## Testing Checklist

### Unit Tests
- [ ] Test responsive layout switching
- [ ] Test cart state preservation
- [ ] Test modal interactions

### Feature Tests
- [ ] Test tablet view renders correctly
- [ ] Test split-screen interactions
- [ ] Test modal payments
- [ ] Test cart management in sidebar

### Browser Tests
- [ ] Test responsive switching at breakpoints
- [ ] Test touch interactions on tablet
- [ ] Test keyboard navigation
- [ ] Test print functionality

---

## Acceptance Criteria

- [ ] Split-screen layout displays on tablet
- [ ] Products grid shows 3-4 columns
- [ ] Cart sidebar always visible
- [ ] Cart updates in real-time
- [ ] Product modal is centered
- [ ] Payment modal is centered
- [ ] Success shows within modal
- [ ] State preserved when resizing
- [ ] Touch interactions work
- [ ] Keyboard navigation works
- [ ] Performance is smooth

---

## Notes

- Consider adding keyboard shortcuts for common actions
- Test on different tablet sizes (iPad, Android tablets)
- Ensure touch targets are large enough (min 44px)
- Consider landscape vs portrait orientation
- May need to adjust cart width based on content

---

## Next Phase

After completing this phase, proceed to [Phase 5: Transaction Management](./PHASE_5.md)