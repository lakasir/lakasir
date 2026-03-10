# Phase 3: POS/Cashier (Mobile Responsive)

**Duration**: 4-5 days  
**Status**: Not Started  
**Dependencies**: Phase 1 (Foundation & Authentication)

---

## Overview

This phase implements the Point of Sale (POS) system for mobile devices (max-width: 768px). The POS is the core functionality of the application, allowing users to create sales transactions.

---

## Figma References

| Screen | Node ID | URL |
|--------|---------|-----|
| Product List | 2-139 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=2-139) |
| Add to Cart Popup | 106-834 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=106-834) |
| Cart Item State | 147-357 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=147-357) |
| Cart List | 153-1067 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=153-1067) |
| Payment Method | 1489-1226 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1226) |
| QR Payment | 1489-1405 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1405) |
| Non-QR Payment | 1492-2231 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2231) |
| Payment Success | 1489-1632 | [View](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1632) |

---

## Prerequisites

- [ ] Phase 1 complete
- [ ] Base UI components available
- [ ] Authentication working
- [ ] API endpoints functional

---

## Architecture

### State Management

The POS uses Livewire's reactive state management:

```
POS State (Livewire)
├── products (paginated list)
├── categories (for filtering)
├── cart (array of cart items)
├── selectedCategory (filter)
├── searchQuery (search filter)
├── selectedProduct (for modal)
└── paymentMethod (selected payment)
```

### Data Flow

```
User Action → Volt Component → State Update → UI Reactivity → API Call (if needed)
                ↓
            Cart Management
                ↓
            Payment Flow
                ↓
            API Transaction
                ↓
            Success/Error State
```

---

## Tasks

### 1. Product List View

**File**: `resources/views/livewire/pos/product-list.blade.php`

**Volt Page**: `resources/views/livewire/pos/product-list.blade.php`

#### 1.1 Product Grid Layout

**Design**: Mobile-optimized grid showing products in cards

**Structure**:
```
┌─────────────────────────────────────┐
│  Header: Search + Cart Icon         │
│  ┌─────────────────────────────┐   │
│  │ 🔍 Search products...       │🛒 │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Category Chips (Horizontal Scroll) │
│  [All] [Food] [Drinks] [Snacks]... │
├─────────────────────────────────────┤
│  Product Grid (2 columns)           │
│  ┌─────────┐ ┌─────────┐           │
│  │ [Img]   │ │ [Img]   │           │
│  │ Name    │ │ Name    │           │
│  │ Price   │ │ Price   │           │
│  │ Stock   │ │ Stock   │           │
│  └─────────┘ └─────────┘           │
│  ┌─────────┐ ┌─────────┐           │
│  │ [Img]   │ │ [Img]   │           │
│  │ Name    │ │ Name    │           │
│  │ Price   │ │ Price   │           │
│  └─────────┘ └─────────┘           │
│                                     │
│  [Load More]                        │
└─────────────────────────────────────┘
│  Cart Summary Bar (Fixed Bottom)    │
│  🛒 3 items | Rp 150.000   [View]   │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create product list component
- [ ] Implement search bar
- [ ] Add category horizontal scroll
- [ ] Create product card component
- [ ] Implement grid layout (2 columns)
- [ ] Add infinite scroll or load more
- [ ] Add cart summary bar (fixed bottom)

#### 1.2 Search Functionality

**API Integration**: Use existing `ProductController@index`

**Features**:
- Search by product name
- Search by barcode
- Debounced search (300ms)
- Clear search button
- Show recent searches (optional)

**Tasks**:
- [ ] Implement search input
- [ ] Add debounce for performance
- [ ] Clear search on category change
- [ ] Show search results count

#### 1.3 Category Filter

**Data Source**: `CategoryController@index`

**Features**:
- Horizontal scrollable chips
- Active state styling
- "All" option
- Category icon/image (optional)

**Tasks**:
- [ ] Fetch categories on mount
- [ ] Create category chip component
- [ ] Implement horizontal scroll
- [ ] Add active state styling
- [ ] Filter products on category select

#### 1.4 Product Card Component

**File**: `resources/views/components/pos/product-card.blade.php`

**Props**:
- `id`: product ID
- `name`: product name
- `price`: product price
- `image`: product image URL
- `stock`: current stock
- `category`: category name

**Features**:
- Product image with fallback
- Product name (truncate if long)
- Price formatted with currency
- Stock indicator (in stock/low/out)
- Tap to add cart

**Tasks**:
- [ ] Create product card HTML
- [ ] Add product image
- [ ] Implement price formatting
- [ ] Add stock indicator
- [ ] Add tap/click handler

#### 1.5 Barcode Scanner (Optional)

**File**: `resources/views/components/pos/barcode-scanner.blade.php`

**Features**:
- Camera-based barcode scanning
- Manual barcode input
- Scan result handling
- Sound feedback on successful scan

**Tasks**:
- [ ] Integrate camera API
- [ ] Implement barcode detection
- [ ] Add barcode sound feedback
- [ ] Handle scanned product lookup

---

### 2. Add to Cart Popup

**File**: `resources/views/livewire/pos/add-to-cart-modal.blade.php`

**Design Reference**: [Figma - Add to Cart](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=106-834)

#### 2.1 Modal Layout

**Structure**:
```
┌─────────────────────────────────────┐
│  [Product Image - Large]            │
│                                     │
├─────────────────────────────────────┤
│  Product Name                       │
│  Category: Food                     │
│  Price: Rp 25.000                   │
│  Stock: 50 available                │
├─────────────────────────────────────┤
│  Select Unit Price:                 │
│  ○ Regular (Rp 25.000)              │
│  ○ Large (Rp 35.000)                │
│  ○ Extra Large (Rp 45.000)          │
├─────────────────────────────────────┤
│  Quantity:                          │
│  [−]  1  [+]                        │
├─────────────────────────────────────┤
│  Notes (Optional):                   │
│  ┌─────────────────────────────┐   │
│  │ Add custom note...          │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Subtotal: Rp 25.000                │
├─────────────────────────────────────┤
│  [Add to Cart]                      │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create modal component
- [ ] Add product image display
- [ ] Show product details
- [ ] Add unit price selector (if multiple)
- [ ] Implement quantity controls
- [ ] Add notes input
- [ ] Calculate and show subtotal
- [ ] Add "Add to Cart" button
- [ ] Slide-up animation (mobile)

#### 2.2 Quantity Controls

**Features**:
- Minus button (minimum: 1)
- Plus button
- Numeric input (editable)
- Long press for rapid increment (optional)

**Tasks**:
- [ ] Create quantity control component
- [ ] Add validation (min: 1, max: stock)
- [ ] Implement keyboard input
- [ ] Add quick quantity buttons (5, 10, 20)

#### 2.3 Unit Price Selection

**Data Source**: Product's price units (if applicable)

**Features**:
- Radio button group
- Price display
- Default to first unit

**Tasks**:
- [ ] Fetch price units for product
- [ ] Create radio button group
- [ ] Update price on selection
- [ ] Auto-select default unit

---

### 3. Cart Management

#### 3.1 Cart State

**Cart Item Structure**:
```php
[
    'id' => 'cart-item-uuid',
    'product_id' => 1,
    'product_name' => 'Product Name',
    'product_image' => 'url',
    'unit_id' => 1,
    'unit_name' => 'Regular',
    'price' => 25000,
    'quantity' => 2,
    'subtotal' => 50000,
    'notes' => 'Extra sauce',
]
```

**Tasks**:
- [ ] Define cart item interface/class
- [ ] Implement cart state in Livewire
- [ ] Persist cart in session/storage
- [ ] Handle cart calculations

#### 3.2 Cart Summary Bar

**File**: `resources/views/components/pos/cart-summary-bar.blade.php`

**Features**:
- Item count
- Total amount
- "View Cart" button
- Fixed at bottom on mobile
- Animate on cart update

**Tasks**:
- [ ] Create cart summary component
- [ ] Show item count
- [ ] Show total formatted
- [ ] Add view cart button
- [ ] Implement show/hide animation

#### 3.3 Cart Item State (Mini Cart)

**Design Reference**: [Figma - Cart Item State](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=147-357)

**Features**:
- Compact cart preview
- Appears when cart icon tapped
- Shows first few items
- "View Full Cart" button
- Quick quantity adjustment

**Tasks**:
- [ ] Create mini cart component
- [ ] Show recent items
- [ ] Add quick edit buttons
- [ ] Link to full cart

#### 3.4 Full Cart List

**File**: `resources/views/livewire/pos/cart.blade.php`

**Design Reference**: [Figma - Cart List](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=153-1067)

**Structure**:
```
┌─────────────────────────────────────┐
│  ← Cart (3 items)                   │
├─────────────────────────────────────┤
│  ┌─────────────────────────────┐   │
│  │ [Img] Product Name          │   │
│  │       Unit: Regular         │   │
│  │       Rp 25.000            │   │
│  │       [−] 2 [+]   Rp 50.000│   │
│  │       🗑️                   │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ [Img] Another Product       │   │
│  │       ...                   │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Notes Field (Global)              │
│  ┌─────────────────────────────┐   │
│  │ Add order notes...          │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Subtotal: Rp 100.000              │
│  Discount: −Rp 5.000                │
│  Total: Rp 95.000                   │
├─────────────────────────────────────┤
│  [Clear Cart]      [Continue]       │
└─────────────────────────────────────┘
```

**Features**:
- List of cart items
- Product image, name, unit
- Quantity controls
- Item price and subtotal
- Remove item button
- Edit item (opens product modal)
- Global notes
- Subtotal, discount, total
- Clear cart button
- Continue to payment button

**Tasks**:
- [ ] Create cart list component
- [ ] Implement cart item row
- [ ] Add quantity controls per item
- [ ] Add remove item functionality
- [ ] Add edit item (reopen modal)
- [ ] Implement global notes
- [ ] Calculate totals
- [ ] Add discount input (optional)
- [ ] Add clear cart function
- [ ] Add continue button

---

### 4. Payment Flow

#### 4.1 Payment Method Selection

**File**: `resources/views/livewire/pos/payment-method.blade.php`

**Design Reference**: [Figma - Payment Method](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1226)

**Data Source**: `PaymentMethodController@index`

**Structure**:
```
┌─────────────────────────────────────┐
│  ← Select Payment Method            │
├─────────────────────────────────────┤
│  Order Summary                      │
│  ┌─────────────────────────────┐   │
│  │ 3 items                     │   │
│  │ Total: Rp 95.000            │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Payment Methods:                   │
│  ┌─────────────────────────────┐   │
│  │ 💵 Cash                      │   │
│  │                             │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ 📱 QRIS / E-Wallet          │   │
│  │ Scan QR to pay              │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ 💳 Card Payment             │   │
│  │ Credit/Debit Card           │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ 🏦 Bank Transfer            │   │
│  │ BCA, Mandiri, BRI           │   │
│  └─────────────────────────────┘   │
│  ┌─────────────────────────────┐   │
│  │ 🎟️ Voucher                  │   │
│  │ Use voucher code            │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Customer (Optional):               │
│  [Select Customer ▼]                │
└─────────────────────────────────────┘
```

**Tasks**:
- [ ] Create payment method component
- [ ] Fetch available payment methods
- [ ] Create payment method cards
- [ ] Add customer selection dropdown
- [ ] Handle payment method selection
- [ ] Navigate to appropriate payment screen

#### 4.2 QR Payment Screen

**Design Reference**: [Figma - QR Payment](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1405)

**Structure**:
```
┌─────────────────────────────────────┐
│  ← QRIS Payment                     │
├─────────────────────────────────────┤
│  ┌─────────────────────────────┐   │
│  │                             │   │
│  │       [QR CODE IMAGE]       │   │
│  │                             │   │
│  └─────────────────────────────┘   │
│                                     │
│  Amount: Rp 95.000                  │
│  Status: Waiting for payment...     │
│                                     │
│  [Refresh QR] [Copy Payment Code]  │
│                                     │
│  Payment Instructions:              │
│  1. Open your e-wallet app          │
│  2. Scan the QR code               │
│  3. Confirm the amount              │
│  4. Complete the payment            │
├─────────────────────────────────────┤
│  ┌─────────────────────────────┐   │
│  │ 🔄 Checking payment status...│   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│           [Cancel]                  │
└─────────────────────────────────────┘
```

**Features**:
- Display QR code image
- Show payment amount
- Poll payment status (every 5 seconds)
- Refresh QR code button
- Copy payment code
- Cancel button

**Tasks**:
- [ ] Create QR payment component
- [ ] Generate QR code image
- [ ] Implement payment polling
- [ ] Add refresh functionality
- [ ] Handle payment success
- [ ] Handle payment timeout
- [ ] Add cancel functionality

#### 4.3 Cash Payment Screen

**Design Reference**: [Figma - Non-QR Payment](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1492-2231)

**Structure**:
```
┌─────────────────────────────────────┐
│  ← Cash Payment                     │
├─────────────────────────────────────┤
│  Order Summary:                     │
│  ┌─────────────────────────────┐   │
│  │ Item 1 x2      Rp 50.000    │   │
│  │ Item 2 x1      Rp 45.000    │   │
│  ─────────────────────────────    │   │
│  │ Total          Rp 95.000    │   │
│  └─────────────────────────────┘   │
├─────────────────────────────────────┤
│  Amount Received:                   │
│  ┌─────────────────────────────┐   │
│  │ Rp [________________]       │   │
│  └─────────────────────────────┘   │
│                                     │
│  Quick Amounts:                     │
│  [100k] [150k] [200k] [Exact]      │
├─────────────────────────────────────┤
│  Change: Rp 5.000                   │
├─────────────────────────────────────┤
│  [Cancel]           [Confirm]       │
└─────────────────────────────────────┘
```

**Features**:
- Order summary display
- Amount received input
- Quick amount buttons
- Change calculation
- Confirm payment button

**Tasks**:
- [ ] Create cash payment component
- [ ] Add amount input
- [ ] Add quick amount buttons
- [ ] Calculate change automatically
- [ ] Add validation (amount >= total)
- [ ] Implement confirm payment

#### 4.4 Payment Success Screen

**Design Reference**: [Figma - Payment Success](https://www.figma.com/design/Nbc5NwW89oB9msV7q55HZP/Redesign-Lakasir--Copy-?node-id=1489-1632)

**Structure**:
```
┌─────────────────────────────────────┐
│                                     │
│            ✅ Success!              │
│                                     │
│  Transaction completed successfully │
│                                     │
│  ┌─────────────────────────────┐   │
│  │ Receipt Preview             │   │
│  │ ─────────────────────────   │   │
│  │ Store Name                  │   │
│  │ ─────────────────────────   │   │
│  │ Item 1 x2      Rp 50.000    │   │
│  │ Item 2 x1      Rp 45.000    │   │
│  │ ─────────────────────────   │   │
│  │ Total          Rp 95.000    │   │
│  │ Cash           Rp 100.000   │   │
│  │ Change          Rp 5.000    │   │
│  │ ─────────────────────────   │   │
│  │ Thank you!                  │   │
│  └─────────────────────────────┘   │
│                                     │
│  [Print Receipt] [New Transaction] │
│                                     │
└─────────────────────────────────────┘
```

**Features**:
- Success animation/icon
- Transaction summary
- Receipt preview
- Print receipt button
- New transaction button
- Receipt auto-print (if enabled)

**Tasks**:
- [ ] Create success screen component
- [ ] Add success animation
- [ ] Show receipt preview
- [ ] Add print functionality
- [ ] Add new transaction button
- [ ] Clear cart after success

---

### 5. Receipt Printing

#### 5.1 Receipt Template

**File**: `resources/views/receipts/thermal.blade.php`

**Features**:
- Store header (name, address, phone)
- Transaction details (ID, date, cashier)
- Item list
- Totals (subtotal, discount, tax, total)
- Payment method
- Footer (thank you message)

**Tasks**:
- [ ] Create receipt template
- [ ] Format for thermal printer (58mm/80mm)
- [ ] Add QR code for digital receipt (optional)

#### 5.2 Print Handler

**Integration**: Use existing USB direct printing from browser

**Features**:
- Print immediately on success
- Reprint option
- Print test page

**Tasks**:
- [ ] Integrate with existing print functionality
- [ ] Handle print errors
- [ ] Add print preview for testing

---

## State Management

### Livewire Properties

```php
// Product List
public string $search = '';
public ?int $selectedCategory = null;
public $products; // Paginated
public $categories;

// Cart
public array $cart = [];
public ?int $selectedProductId = null;
public bool $showProductModal = false;

// Payment
public ?int $selectedPaymentMethod = null;
public ?int $selectedCustomerId = null;
public string $paymentStatus = 'pending';
public ?string $qrCode = null;

// Modals
public bool $showCartModal = false;
public bool $showPaymentModal = false;
```

### Livewire Methods

```php
// Product methods
public function searchProducts(): void {}
public function selectCategory(int $categoryId): void {}
public function loadMore(): void {}

// Cart methods
public function addToCart(array $item): void {}
public function updateQuantity(int $itemId, int $quantity): void {}
public function removeFromCart(int $itemId): void {}
public function clearCart(): void {}

// Payment methods
public function selectPaymentMethod(int $methodId): void {}
public function processPayment(): void {}
public function checkPaymentStatus(): void {}

// Navigation
public function showProduct(int $productId): void {}
public function showCart(): void {}
public function showPayment(): void {}
public function newTransaction(): void {}
```

---

## Routes

```php
use Livewire\Volt\Volt;

Route::middleware('auth')->prefix('member')->group(function () {
    Volt::route('/pos', 'pos/main')->name('pos.index');
    Volt::route('/pos/cart', 'pos/cart')->name('pos.cart');
    Volt::route('/pos/payment', 'pos/payment')->name('pos.payment');
});
```

---

## API Endpoints (Existing)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/tenant/master/products` | GET | List products |
| `/api/tenant/master/categories` | GET | List categories |
| `/api/tenant/master/products/search` | GET | Search products |
| `/api/tenant/transaction/sellings` | POST | Create transaction |
| `/api/tenant/master/payment-methods` | GET | List payment methods |
| `/api/tenant/master/members` | GET | List members |

---

## Files to Create

### Volt Pages
```
resources/views/livewire/pos/
├── main.blade.php
├── product-list.blade.php
├── cart.blade.php
├── payment.blade.php
├── payment-method.blade.php
├── qr-payment.blade.php
└── cash-payment.blade.php
```

### Views
```
resources/views/livewire/pos/
├── main.blade.php
├── product-list.blade.php
├── cart.blade.php
├── payment.blade.php
├── payment-method.blade.php
├── qr-payment.blade.php
└── cash-payment.blade.php
```

### Components
```
resources/views/components/pos/
├── product-card.blade.php
├── product-modal.blade.php
├── cart-item.blade.php
├── cart-summary-bar.blade.php
├── mini-cart.blade.php
├── payment-method-card.blade.php
└── receipt.blade.php
```

---

## Testing Checklist

### Unit Tests
- [ ] Test cart add/remove/update
- [ ] Test total calculation
- [ ] Test discount application
- [ ] Test payment status polling

### Feature Tests
- [ ] Test product list renders
- [ ] Test search functionality
- [ ] Test category filter
- [ ] Test cart management
- [ ] Test payment creation
- [ ] Test transaction completion

### Browser Tests
- [ ] Test full POS flow (add to cart → pay → success)
- [ ] Test mobile responsiveness
- [ ] Test barcode scanner (if implemented)
- [ ] Test print receipt

---

## Acceptance Criteria

- [ ] Product grid displays correctly on mobile
- [ ] Search finds products by name/barcode
- [ ] Category filter works correctly
- [ ] Add to cart modal opens on product tap
- [ ] Quantity controls work correctly
- [ ] Unit price selection works (if applicable)
- [ ] Cart displays all items correctly
- [ ] Cart quantity can be updated
- [ ] Items can be removed from cart
- [ ] Cart can be cleared
- [ ] Notes can be added to order/items
- [ ] Payment method selection works
- [ ] QR payment displays QR code
- [ ] QR payment polls for status
- [ ] Cash payment calculates change
- [ ] Payment success shows receipt
- [ ] Receipt can be printed
- [ ] New transaction clears cart
- [ ] All interactions are touch-friendly
- [ ] Performance is acceptable (< 3s load)

---

## Notes

- Consider offline mode support (store cart in localStorage)
- Real-time cart sync may be needed for tablet view (Phase 4)
- Barcode scanning requires HTTPS for camera access
- Receipt printing depends on browser USB support
- Payment polling should have timeout

---

## Next Phase

After completing this phase, proceed to [Phase 4: POS/Cashier (Tablet)](./PHASE_4.md)