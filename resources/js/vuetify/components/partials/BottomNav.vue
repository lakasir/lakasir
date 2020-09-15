<template>
  <v-bottom-navigation
    fixed
    :value="value"
    color="primary"
    >
    <v-btn :to="{ name: 'cashier.activity' }">
      <span>{{ __('app.sellings.menu.activity') }}</span>
      <v-icon>mdi-history</v-icon>
    </v-btn>
    <v-btn :to="{ name: 'cashier.selling' }">
      <span>{{ __('app.sellings.menu.sell') }}</span>
      <v-icon>mdi-cart</v-icon>
    </v-btn>
    <v-btn :to="{ name: 'cashier.profile' }">
      <span>{{ __('app.sellings.menu.profile') }}</span>
      <v-icon>mdi-face</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {
  name: 'BottomNav',

  props: {

  },

  data: () => ({ value: 1 }),

  methods: {

  },

  mounted() {
  }
}

</script>
