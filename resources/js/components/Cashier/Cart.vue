<template>
  <div>
    <div class="card">
      <div class="card-header">
        {{ __('app.sellings.carts') }}
      </div>
      <div class="card-body">
        <table-cart></table-cart>
        <button class="btn btn-block btn-primary" v-if="cartCount > 0" @click="() => open = true">{{ __('app.global.payit') }}</button>
      </div>
      <modal :title="__('app.sellings.submit_order')" @closed="closed" @submit="save" :open="open">
        <submit :reset="open" :save="submitItem" @closed="closed"></submit>
      </modal>
    </div>
  </div>
</template>

<script>
import TableCart from './Cart/Table';
import Modal from './../Modal/Modal';
import Submit from './Submit';
import { mapState } from 'vuex';

export default {
  name: 'Cart',
  components: {
    TableCart,
    Modal,
    Submit
  },

  props: {
  },

  computed: {
    ...mapState('cart', {
      cartCount: state => state.cartCount,
    }),
  },

  data() {
    return {
      open: false,
      submitItem: false
    }
  },

  methods: {
    closed() {
      this.open = false
    },

    save() {
      this.submitItem++
    },
  },

  mounted() {
  }
}

</script>
