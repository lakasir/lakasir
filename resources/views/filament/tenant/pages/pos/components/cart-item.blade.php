<div x-data="cart()"
    x-init="$watch('$wire.cartItems', value => cart = value)"
    class="relative min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="fixed top-0 left-0 right-0 bg-white z-10 shadow-sm">
    <div class="max-w-4xl mx-auto">
      <div class="flex items-center justify-between p-4">
        <button @click="goToMenu"
          class="text-gray-800">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </button>
        <h1 class="text-xl font-semibold">Cart</h1>
        <button class="text-gray-800">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Cart Items -->
  <div class="max-w-4xl mx-auto p-4 pt-20 pb-40">
    <div class="space-y-4">
      <template x-for="(cartItem, itemId) in cart" :key="itemId">
        <div class="flex items-center space-x-4 bg-white rounded-lg p-4">
          <img :src="cartItem.product.hero_images[0]" class="w-24 h-24 object-cover rounded-lg">
          <div class="flex-1">
            <h3 class="font-medium" x-text="cartItem.product.name"></h3>
            <p class="text-sm text-gray-500" x-text="cartItem.product.description"></p>
            <p class="mt-1">
            <span class="text-gray-500">Total Price</span>
            </p>
            <p>
              <span class="text-lakasir-primary text-sm font-normal">Rp. </span>
              <span class="font-semibold" x-text="moneyFormat(cartItem.price)"></span>
            </p>
          </div>
          <div class="flex items-center space-x-3">
            <button
              @click="await $wire.decrementQuantity(cartItem.product_id)"
              class="w-8 h-8 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center"
              >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button>
            <span class="w-8 text-center" x-text="cartItem.qty"></span>
            <button
              @click="await $wire.incrementQuantity(cartItem.product_id)"
              class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center"
              >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </button>
          </div>
        </div>
      </template>
    </div>

    <!-- Add More Button -->
    <button
      @click="goToMenu"
      class="w-full mt-6 p-4 border-2 border-orange-500 text-orange-500 rounded-full font-medium flex items-center justify-center"
      >
      <span class="mr-2">+</span>
      Add More
    </button>
  </div>

  <!-- Summary Footer -->
  <div class="fixed bottom-0 left-0 right-0 bg-white border-t">
    <div class="max-w-4xl mx-auto p-4">
      <div class="space-y-3">
        <div class="flex justify-between text-gray-600">
          <span>Subtotal</span>
          <span x-text="'Rp. ' + moneyFormat(cartTotal)"></span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Discount</span>
          <span>Rp. 0</span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Member</span>
          <span>No Member</span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Tax (10%)</span>
          <span x-text="'Rp. ' + moneyFormat(cartTotal * 0.1)"></span>
        </div>
        <div class="flex justify-between font-medium text-lg">
          <span>Total</span>
          <span class="text-orange-500" x-text="'Rp. ' + moneyFormat(cartTotal * 1.1)"></span>
        </div>
      </div>

      <button
        @click="continueToPayment"
        class="w-full mt-6 px-4 py-6 bg-lakasir-primary text-white rounded-full font-medium"
        >
        Continue Payment
      </button>
    </div>
  </div>
</div>

@script()
<script>
  Alpine.data('cart', () => {
    return {
      cart: @json($cartItems) || {},

      init() {
        Livewire.on('cartUpdated', (newCartItems) => {
          this.cart = newCartItems[0];
        });
      },

      get cartTotal() {
        return Object.values(this.cart).reduce((total, cartItem) => {
          return total + (cartItem.price * cartItem.qty);
        }, 0);
      },

      goToMenu() {
        Livewire.navigate('/member/p-o-s')
      },

      continueToPayment() {
        console.log('Proceeding to payment...');
        console.log('Cart Total:', this.cartTotal);
        console.log('Tax:', this.cartTotal * 0.1);
        console.log('Final Total:', this.cartTotal * 1.1);
      }
    }
  })
</script>
@endscript
