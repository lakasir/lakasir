<template>
  <div>
    <top-nav></top-nav>
    <v-snackbar
      v-model="$store.state.snackbar"
      :timeout="timeout"
      >
      {{ $store.state.message }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="blue"
          text
          v-bind="attrs"
          @click="snackbar = false"
          >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import TopNav from './TopNav.vue';
export default {
  components: {
    TopNav
  },
  name: 'Master',

  props: {

  },

  data: () => ({
    snackbar: false,
    timeout: 2000,
  }),

  methods: {

  },

  mounted() {
    console.log(this.$vuetify.breakpoint.smOnly);
  }
}

</script>

<style lang="sass">

@media only screen and (min-width: 960px)
.container
  max-width: 960px

</style>
