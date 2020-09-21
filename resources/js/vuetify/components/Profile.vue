<template>
  <div>
    <div class="d-flex justify-center">
      <div class="purple lighten-5 rounded-circle thumbnail pa-2">
        <v-img
          class=" rounded-circle thumbnail"
          :lazy-src="profile.image"
          max-width="250"
          height="250"
          width="250"
          :src="profile.image"
          ></v-img>
      </div>
    </div>
    <div>
      <v-list two-line>
        <v-list-item>
          <v-list-item-icon>
            <v-icon color="indigo">
              mdi-phone
            </v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ profile.phone }}</v-list-item-title>
            <v-list-item-subtitle>Mobile</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-divider inset></v-divider>

        <v-list-item>
          <v-list-item-icon>
            <v-icon color="indigo">
              mdi-email
            </v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ profile.email }}</v-list-item-title>
            <v-list-item-subtitle>Personal</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-divider inset></v-divider>

        <v-list-item>
          <v-list-item-icon>
            <v-icon color="indigo">
              mdi-map-marker
            </v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ profile.address }}</v-list-item-title>
            <v-list-item-subtitle>Address</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
  name: 'Profile',

  computed: mapState('profiles', {
    profile: state => state.profile
  }),

  methods: {
    ...mapActions('profiles', [
      'getProfile'
    ])
  },

  mounted() {
    this.getProfile()
  }
}

</script>
