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
        :rules="emailRules"
        :counter="10"
        :label="__('app.auth.placeholder.email')"
        required
        ></v-text-field>
      <v-text-field
        v-model="form.password"
        :rules="passwordRules"
        :counter="10"
        :label="__('app.auth.placeholder.password')"
        required
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
    valid: false,
    passwordRules: [
      v => !!v || 'Password is required',
    ],
    email: '',
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+/.test(v) || 'E-mail must be valid',
    ],
  }),

  methods: {
    loginSubmit() {
      this.$store.dispatch('auth/loginSubmit', {
        email: this.form.email,
        password: this.form.password
      })
        .then(res => {
          this.$router.push('/')
        })
        .catch(err => {
          if (err?.request?.status == 422) {

          }
        })
    }
  },

  mounted() {
    this.config = config
  }
}

</script>
