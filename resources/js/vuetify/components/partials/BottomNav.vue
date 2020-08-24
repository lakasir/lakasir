<template>
  <v-bottom-navigation
    fixed
    :value="value"
    color="primary"
    >
    <v-btn>
      <span>Recents</span>
      <v-icon>mdi-history</v-icon>
    </v-btn>
    <v-btn>
      <span>Favorites</span>
      <v-icon>mdi-heart</v-icon>
    </v-btn>
    <v-btn>
      <span>Nearby</span>
      <v-icon>mdi-map-marker</v-icon>
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
