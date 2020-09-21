<template>
  <div>
    <div class="form-group">
      <select class="form-control" v-model="form.payment_method_id"
             :class="errors.payment_method_id ? 'is-invalid' : ''">
        <option value="null">{{ __('app.sellings.payment_method_id') }}</option>
        <option v-for="paymentMethod in paymentMethods" :value="paymentMethod.value" v-text="paymentMethod.text"
        ></option>
      </select>
    </div>
    <div class="form-group">
      <input class="form-control"
             :class="errors.money ? 'is-invalid' : ''"
             type="text"
             @focus="onFocus"
             @blur="onBlur"
             @keyup="getCalculatedRefund"
             v-model="money" :placeholder="__('app.sellings.money')">
    </div>
    <div class="form-group">
      <input class="form-control" type="text" :value="priceFormat(totalPrice)" readonly :placeholder="__('app.sellings.total_price')">
    </div>
    <div class="form-group">
      <input class="form-control" type="text" :value="priceFormat(refund)" readonly :placeholder="__('app.sellings.refund')">
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex';

export default {
  name: 'Submit',

  props: {
    reset: { default: false },
    save: { default: false }
  },

  data() {
    return {
      money: 0,
      moneyFormat: 0,
      realMoney: 0,
      dialog: false,
      form: {
        payment_method_id: null
      }
    }
  },
  computed: {
    ...mapState('paymentMethods', {
      paymentMethods: state => state.paymentMethods
    }),
    ...mapState('cart', {
      cartItems: state => state.cartItems,
    }),
    ...mapState('items', {
      errors: state => state.errors
    }),
    ...mapGetters('cart', {
      totalPrice: 'getTotalPrice',
      refund: 'getRefund',
    })
  },

  methods: {
    ...mapActions('paymentMethods', [
      'getPaymentMethods'
    ]),
    ...mapActions('cart', {
      resetCart: 'reset',
      calculateRefund: 'calculateRefund',
    }),
    ...mapActions('items', [
      'getItem',
      'submit',
    ]),
    onBlur() {
      this.money = this.priceFormat(this.moneyFormat)
    },
    onFocus(e) {
      this.money = this.realMoney
      e.target.select()
    },
    cancel() {
      this.moneyFormat = 0
      this.realMoney = 0
      this.form.payment_method_id = null
      this.$emit('closed', this.dialog)
    },
    getCalculatedRefund(event) {
      this.moneyFormat = event.target.value
      this.realMoney = event.target.value
      this.calculateRefund(event.target.value)
    },
  },

  watch: {
    reset: function(val) {
      if (!val) {
        this.moneyFormat = 0
        this.realMoney = 0
        this.money = 0
      }
    },
    save: function(val) {
      this.form.money = this.realMoney
      this.form.items = this.cartItems
      this.submit(this.form)
        .then(res => {
          this.moneyFormat = 0;
          this.realMoney = 0;
          this.money = 0;
          this.resetCart();
          this.$emit('closed', this.dialog)
          this.$notify({
            group: 'foo',
            title: this.$store.state.message,
          });
          this.getItem();
        })
    }
  },

  mounted() {
    this.getPaymentMethods({
      type: 'select2',
      term: '',
      key: 'name',
      filter: {
        key: 'visible_in->selling',
        value: 'true'
      }
    })
  }
}

</script>
