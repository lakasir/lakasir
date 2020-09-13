<template>
  <v-row dense class="mb-5">
    <v-col
      v-for="item in items"
      :key="item.id"
      :cols="6"
      >
      <v-card>
        <v-img
          :src="item.image"
          class="white--text align-end"
          gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
          height="200px"
          >
          <v-card-title v-text="item.name"></v-card-title>
        </v-img>
      <v-card-text v-text="priceFormat(item.selling_price)"></v-card-text>
      <v-card-actions>
        <v-card-text>Stock: {{ item.stock }}</v-card-text>
        <v-spacer></v-spacer>

        <v-btn icon @click="addToCart({
            id: item.id,
            name: item.name,
            sellingPrice: item.selling_price,
            totalPrice: item.selling_price,
            src: item.image,
            qty: 1,
            maxStock: item.stock
            })" v-if="item.stock > 0">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-card-actions>
      </v-card>
    </v-col>
    <div style="height: 23em">

    </div>
  </v-row>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'Sell',

  props: {

  },

  data() {
    return {

    }
  },

  computed: mapState('items', {
    items: state => state.items
  }),

  methods: {
    ...mapActions('items', [
      'getItem',
    ]),
    ...mapActions('carts', [
      'addToCart'
    ])
  },

  mounted() {
    let search = this.$route.query.search ?? ''
    if (search == '') {
      this.getItem()
    }
  }
}

</script>
