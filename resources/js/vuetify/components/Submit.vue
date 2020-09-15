<template>
  <div>
      <v-dialog
      v-model="dialog"
      persistent
      max-width="600px"
    >
    <v-card>
        <v-card-title>
          <span class="headline">{{ __('app.sellings.title.submit') }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="12"
                sm="6"
                >
                <v-autocomplete
                  :items="paymentMethods"
                  :label="__('app.sellings.column.payment_method')"
                  hide-details
                  :error-messages="error.payment_method_id"
                  v-model="form.payment_method_id"
                  @keyup="searchPaymentMethods"
                  @focus="searchPaymentMethods"
                  ></v-autocomplete>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  :label="__('app.sellings.column.money')"
                  hide-details
                  required
                  number
                  v-model="money"
                  :error-messages="error.money"
                  @focus="onFocus"
                  @blur="onBlur"
                  @keyup="getCalculatedRefund"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
              >
              <v-text-field
                :value="priceFormat(totalPrice)"
                label="app.sellings.column.total_price"
                hide-details
                readonly
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
              >
              <v-text-field
                :value="priceFormat(refund)"
                label="app.sellings.column.refund"
                hide-details
                readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="cancel"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="save"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
      </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex';

export default {
  name: 'Submit',

  props: {
    open: Boolean,
  },

  data() {
    return {
      dialog: false,
      search: '',
      money: 0,
      moneyFormat: 0,
      realMoney: 0,
      form: {
        money: '',
        payment_method_id: '',
        items: ''
      }
    }
  },

  watch: {
    open: function (open) {
      this.dialog = open;
    },
  },

  computed: {
    ...mapState('paymentMethods', {
      paymentMethods: state => state.paymentMethods
    }),
    ...mapState('carts', {
      cartItems: state => state.cartItems,
    }),
    ...mapState('items', {
      error: state => state.errors
    }),
    ...mapGetters('carts', {
      totalPrice: 'getTotalPrice',
      refund: 'getRefund',
    })
  },

  methods: {
    ...mapActions('paymentMethods', [
      'getPaymentMethods'
    ]),
    ...mapActions('items', [
      'submit',
    ]),
    ...mapActions('carts', [
      'calculateRefund',
      'reset',
    ]),
    onBlur() {
      this.money = this.priceFormat(this.moneyFormat)
    },
    onFocus() {
      this.money = this.realMoney
    },
    save() {
      this.form.money = this.realMoney
      this.form.items = this.cartItems
      this.submit(this.form)
        .then(res => {
          this.reset()
          this.realMoney = 0
          this.moneyFormat = 0
          this.dialog = false
          this.$emit('closed', this.dialog)
        });
    },
    cancel() {
      this.moneyFormat = 0
      this.realMoney = 0
      this.dialog = false
      this.$emit('closed', this.dialog)
    },
    searchPaymentMethods(event) {
      this.getPaymentMethods({
        type: 'select2',
        term: event.target.value,
        key: 'name',
        filter: {
          key: 'visible_in->selling',
          value: 'true'
        }
      })
    },
    getCalculatedRefund(event) {
      this.moneyFormat = event.target.value
      this.realMoney = event.target.value
      this.calculateRefund(event.target.value)
    }
  },

  mounted() {
  }
}

</script>
