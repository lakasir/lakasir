<template>
  <div>
    <v-input
      :prepend="true"
      :placeholder="__('app.sellings.placeholder.search_item')"
      @event="search"
      icon="fa-search"
      ></v-input>
    <div class="row" :class="items.length > 20 ? 'scrollable' : ''">

      <div class="col-md-4 col-sm-6 col-lg-4 " v-for="item in items">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title" v-text="item.name"></h5>
            <p class="card-text" v-text="item.selling_price_format"></p>
            <button class="btn btn-primary" @click="addToCart({
            id: item.id,
            name: item.name,
            sellingPrice: item.selling_price,
            totalPrice: item.selling_price,
            src: item.image,
            qty: 1,
            maxStock: item.stock
            })" v-if="item.stock > 0"><i class="fas fa-plus"></i></button>
            <button class="btn btn-info" v-text="`${item.stock} - ${item.unit_name}`"></button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'Item',
  computed: mapState('items', {
    items: state => state.items
  }),

  methods: {
    ...mapActions('items', [
      'getItem',
    ]),
    ...mapActions('cart', [
      'addToCart'
    ]),
    search(e) {
      let search = e.target.value
      this.getItem({
        search: search
      })
    }
  },

  mounted() {
    this.getItem()
  }
}

</script>
