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
        label="Email"
        required
        ></v-text-field>
      <v-text-field
        v-model="form.password"
        :rules="passwordRules"
        :counter="10"
        label="Password"
        required
        type="password"
        ></v-text-field>
      <br>
        <v-btn
              color="blue darken-1"
              dark
              block
              >
              Login
            </v-btn>
      <br>
      <router-link :to="{ path: 'forgot-password' }" class="text-decoration-none">
          <p class="text-center">Forgot Password</p>
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

  },

  mounted() {
    this.config = config
  }
}

</script>
