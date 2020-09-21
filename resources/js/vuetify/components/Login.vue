<template>
  <v-app class="fill-height">
    <v-container style="height: 300px" class="blue darken-1 d-flex justify-center">
      <v-img
        class="align-self-center"
        max-width="300"
        max-height="300"
        :lazy-src="config.logo"
        :src="config.logo"
  ></v-img>
    </v-container>
    <v-container>
      <v-text-field
        v-model="form.email"
        :error-messages="error.email"
        :counter="10"
        :label="__('app.auth.placeholder.email')"
        ></v-text-field>
      <v-text-field
        v-model="form.password"
        :error-messages="error.password"
        :counter="10"
        :label="__('app.auth.placeholder.password')"
        type="password"
        ></v-text-field>
      <br>
        <v-btn
          @click="loginSubmit"
              color="blue darken-1"
              dark
              block
              >
              {{ __('app.auth.login') }}
            </v-btn>
      <br>
      <router-link :to="{ path: 'forgot-password' }" class="text-decoration-none">
        <p class="text-center">{{ __('app.auth.forgot_password') }}</p>
      </router-link>
    </v-container>
  </v-app>
</template>

<script>
const config = require('./../config/app').default;
import { mapState } from 'vuex'

export default {
  name: 'Login',

  props: {

  },

  data: () => ({
    config: '',
    form: {
      email: '',
      password: ''
    },
    error: {
      email: '',
      password: '',
    }
  }),

  computed: mapState('auth', {
    errors: state => state.errors,
    status: state => state.status
  }),

  watch: {
    errors: function(val) {
      this.error.email = val.email
      this.error.password = val.password
    },
  },

  methods: {
    loginSubmit() {
      this.$store.dispatch('auth/loginSubmit', {
        email: this.form.email,
        password: this.form.password
      }).then(res => {
        window.location = '/c'
      })
    }
  },

  mounted() {
    this.config = config
  }
}

</script>
