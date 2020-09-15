<template>
  <v-app>
    <router-view></router-view>
  </v-app>
</template>

<script>
import Master from './components/partials/Master.vue';
export default {
  components: {
    Master
  },
  name: "App",
  watch: {
    '$route' (to, from) {
      document.title = to.meta.title || 'Lakasir'
    }
  }
};
</script>

<style lang="sass">

@media only screen and (min-width: 390)
.container
  max-width: 550px

</style>
