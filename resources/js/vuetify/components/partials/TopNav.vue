<template>
  <div
    max-width="500"
    class="mx-auto"
  >
    <v-app-bar
      color="deep-purple accent-4"
      clipped-left
      app
      dense
      dark
      fixed
    >
      <v-app-bar-nav-icon></v-app-bar-nav-icon>

      <v-toolbar-title v-text="$route.meta.title"></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-barcode-scan</v-icon>
      </v-btn>

      <v-btn icon @click="openSearch = true">
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
      <v-btn icon :to="{ name: 'cashier.selling.cart' }">
        <v-badge
          v-if="cartCount > 0"
          :content="cartCount"
          color="red"
          :messages="cartCount"
          overlap
          >
          <v-icon>mdi-basket</v-icon>
        </v-badge>
        <v-icon v-else>mdi-basket</v-icon>
      </v-btn>

    </v-app-bar>
    <v-main>
      <v-container fluid>
        <search :show="openSearch" @closed="openSearch = false"></search>
        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-main>
    <bottom-nav></bottom-nav>
  </div>
</template>

<script>
import BottomNav from './BottomNav.vue';
import Search from './Search.vue';
import { mapActions, mapState } from 'vuex'

export default {
  components: {
    BottomNav,
    Search
  },
  props: {

  },

  data: () => ({
    selection: [],
    openSearch: false
  }),

  computed: mapState('carts', {
    cartCount: state => state.cartCount
  }),

  methods: {

  },

  mounted() {
  }
}

</script>
