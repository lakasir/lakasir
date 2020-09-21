<template>
  <div class="table-responsive">
    <table class="table no-border">
      <tr>
        <th colspan="1">{{ __('app.sellings.total_price') }}:</th>
        <th v-text="priceFormat(totalPrice)"></th>
      </tr>
      <tr v-for="( cart, i ) in cartItems">
        <th v-text="cart.name"></th>
        <td v-text="priceFormat(cart.totalPrice)"></td>
        <td v-text="cart.qty"></td>
        <td>
          <button class="btn btn-sm btn-primary"
                  @click="addQty({
                    id: cart.id
                  })"><i class="fas fa-plus"></i>
          </button>
          <button class="btn btn-sm btn-danger"
                  @click="removeItem({
                    id: cart.id
                  })"><i class="fas fa-trash"></i>
          </button>

        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex';

export default {
  name: 'Table',

  data() {
    return {

    }
  },
  computed: {
    ...mapState('cart', {
      cartItems: state => state.cartItems,
      cartCount: state => state.cartCount
    }),
    ...mapGetters('cart', {
      totalPrice: 'getTotalPrice',
      refund: 'getRefund',
    })
  },

  methods: {
    ...mapActions('cart', [
      'addQty',
      'reduceQty',
      'removeItem',
      'reset'
    ])
  },
  mounted() {
  }
}

</script>
