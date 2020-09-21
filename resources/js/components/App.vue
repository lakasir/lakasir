<template>
  <div class="row">
  <notifications group="foo" position="bottom right"/>
    <div class="col-sm-7">
      <item></item>
    </div>
    <div class="col-sm-5">
      <cart></cart>
    </div>
  </div>
</template>

<script>
import Item from './Cashier/Item.vue';
import Cart from './Cashier/Cart.vue';

export default {
  name: 'App',
  props: ['token'],
  components: {
    Item,
    Cart,
  },
  mounted() {
    localStorage.setItem('token', this.token)
  }
}

</script>
