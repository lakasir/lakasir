<div
  x-data="cartButton()"
>
  <!-- Trigger Button -->
  <button
    @click="openModal(item)"
    class="w-10 h-10 rounded-full text-white flex items-center justify-center flex-shrink-0"
    :class="item.stock === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-orange-500'"
    :disabled="item.stock === 0"
    >
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
  </button>

    <!-- Modal -->
    <div
      x-show="open"
      x-cloak
      class="relative z-50"
      @keydown.escape.window="open = false"
      >
      <!-- Backdrop -->
      <div
        x-show="open"
        class="fixed inset-0 bg-black/50"
        @click="open = false"
        ></div>

      <!-- Modal Content -->
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            x-show="open"
            x-transition
            @click.away="open = false"
            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 shadow-xl transition-all w-full max-w-sm"
            >
            <!-- Close Button -->
            <div class="absolute right-4 top-4">
              <button
                type="button"
                class="text-gray-400 hover:text-gray-500"
                @click="open = false"
                >
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Content -->
            <div class="mt-3">
              <!-- Product Image -->
              <div class="aspect-w-16 aspect-h-12 mb-4">
                <img
                :src="modalItem?.hero_image"
                :alt="modalItem?.name"
                class="rounded-lg object-cover w-full h-48 object-center"
                />
              </div>

              <!-- Product Details -->
              <h3 class="text-xl font-semibold text-gray-900" x-text="modalItem?.name"></h3>
              <p class="mt-1 text-sm text-gray-500" x-text="modalItem?.description"></p>

              <!-- Price and Stock -->
              <div class="mt-2 flex items-center justify-between">
                <span class="text-sm text-gray-600" x-text="`Stock: ${modalItem?.stock} pcs`"></span>
                <span class="text-sm font-semibold">
                  <span class="text-sm font-normal">Rp</span>
                  <span x-text="moneyFormat(modalItem?.selling_price)"></span>
                </span>
              </div>

              <!-- Quantity Controls -->
              <div class="mt-4">
                <label class="text-sm text-gray-700 font-medium">Quantity</label>
                <div class="mt-1 flex items-center gap-2">
                  <button
                    @click="decrementQuantity"
                    class="w-10 h-10 rounded-full border-2 border-orange-500 text-orange-500 flex items-center justify-center hover:bg-orange-50"
                    :disabled="quantity <= 1"
                    :class="{ 'opacity-50 cursor-not-allowed': quantity <= 1 }"
                    >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>

                  <input
                  type="number"
                  x-model="quantity"
                  @input="validateQuantity"
                  class="block w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                  min="1"
                  :max="modalItem?.stock"
                  />

                  <button
                    @click="incrementQuantity"
                    class="w-10 h-10 rounded-full border-2 border-orange-500 text-orange-500 flex items-center justify-center hover:bg-orange-50"
                    :disabled="quantity >= modalItem?.stock"
                    :class="{ 'opacity-50 cursor-not-allowed': quantity >= modalItem?.stock }"
                    >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Total Price -->
              <div class="mt-3 p-3 bg-orange-50 rounded-lg">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-medium text-gray-700">Total Price</span>
                  <span class="text-lg font-semibold text-orange-500">
                    <span class="text-sm font-normal">Rp</span>
                    <span x-text="moneyFormat(modalItem?.selling_price * quantity)"></span>
                  </span>
                </div>
              </div>

              <!-- Note Input -->
              <div class="mt-4">
                <label class="text-sm text-gray-700 font-medium">Note (Optional)</label>
                <textarea
                  x-model="note"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                  placeholder="Add special instructions..."
                  ></textarea>
              </div>

              <!-- Action Buttons -->
              <div class="mt-5 flex gap-3">
                <button
                  type="button"
                  class="inline-flex w-full justify-center rounded-full border border-gray-300 bg-white px-4 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                  @click="open = false"
                  >
                  Cancel
                </button>
                  <button
                    type="button"
                    @click="addToCart(modalItem.id, { amount: quantity, note: note }); open = false"
                    class="inline-flex w-full justify-center rounded-full border border-transparent bg-orange-500 px-4 py-4 text-sm font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    >
                    + Add to Cart
                  </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@script()
<script>
  Alpine.data('cartButton', () => ({
    open: false,
    modalItem: null,
    note: '',
    quantity: 1,
    openModal(currentItem) {
      this.modalItem = currentItem;
      this.quantity = 1;
      this.note = '';
      this.open = true;
    },
    incrementQuantity() {
      if (this.quantity < this.modalItem?.stock) {
        this.quantity++;
      }
    },
    decrementQuantity() {
      if (this.quantity > 1) {
        this.quantity--;
      }
    },
    validateQuantity() {
      const qty = parseInt(this.quantity);
      if (isNaN(qty) || qty < 1) {
        this.quantity = 1;
      } else if (qty > this.modalItem?.stock) {
        this.quantity = this.modalItem.stock;
      } else {
        this.quantity = qty;
      }
    }
  }))
</script>
@endscript
