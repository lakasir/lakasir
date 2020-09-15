<template>
  <div>
    <v-row dense>
      <v-col
        v-for="(cart, i) in cartItems"
        :key="i"
        cols="12"
        >
        <v-card
          color="white"
          >
          <div class="d-flex flex-no-wrap justify-space-between">
            <div>
              <v-card-title
                class="headline"
                v-text="cart.name"
                ></v-card-title>

              <v-card-subtitle v-text="priceFormat(cart.totalPrice)"></v-card-subtitle>

              <v-card-actions>
                <v-btn
                  class="ml-2"
                  fab
                  icon
                  height="20px"
                  width="20px"
                  @click="reduceQty({
                    id: cart.id
                  })"
                  >
                  <v-icon>mdi-minus</v-icon>
                </v-btn>
                <v-btn
                  fab
                  icon
                  height="20px"
                  width="20px"
                  >
                  {{ cart.qty }}
                </v-btn>
                <v-btn
                  fab
                  icon
                  height="20px"
                  width="20px"
                  @click="addQty({
                    id: cart.id
                  })"
                  >
                  <v-icon>mdi-plus</v-icon>
                </v-btn>
                <v-btn
                  fab
                  icon
                  height="20px"
                  width="20px"
                  @click="removeItem({
                    id: cart.id
                  })"
                  >
                  <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
              </v-card-actions>
            </div>

            <v-avatar
              class="ma-3"
              size="125"
              tile
              >
              <v-img :src="cart.src"></v-img>
            </v-avatar>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12">
        <div v-if="cartCount == 0">
          <v-alert
            text
            prominent
            type="error"
            icon="mdi-cloud-alert"
            >
            {{ __('app.sellings.cart.empty') }}
          </v-alert>
        </div>
        <v-btn
          @click="() => openSubmit = true" dark block :disabled="cartCount == 0"
          color="deep-purple accent-4">{{ __('app.sellings.cart.submit_order') }}</v-btn>
        <v-btn
          @click="() => reset()" dark block :disabled="cartCount == 0"
          class="mt-2"
          color="red darken-3">{{ __('app.sellings.cart.reset') }}</v-btn>
      </v-col>
    </v-row>
    <submit :open="openSubmit" @closed="openSubmit = false">
    </submit>
    <br></br>
  </div>
</template>

<script>
import Submit from './Submit.vue';
import { mapState, mapActions } from 'vuex';
export default {
  name: 'Cart',

  components: {
    Submit
  },

  props: {

  },

  data() {
    return {
      openSubmit: false
    }
  },

  computed: mapState('carts', {
    cartItems: state => state.cartItems,
    cartCount: state => state.cartCount
  }),

  methods: {
    ...mapActions('carts', [
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
