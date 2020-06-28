<template>
  <div>
    <div class="mb-3" :class="prepend ? 'input-group' : ''">
      <label v-if="!prepend">{{ label }}</label>
      <input type="text" class="form-control" :class="error ? 'is-invalid' : validClass"
              :name="name" v-on:keyup="checkValidation" :placeholder="placeholder">
      <div v-if="prepend" class="input-group-append">
        <div class="input-group-text">
          <span :class="'fas ' + icon"></span>
        </div>
      </div>
      <div v-if="error" class="invalid-feedback">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VInput',

  props: {
    name: {
      type: String,
      value: ''
    },
    icon : {
      type: String,
      value: 'fa-user'
    },
    prepend : {
      type: Boolean,
      value: false
    },
    info : {
      type: String,
      value: ''
    },
    label : {
      type: String,
      value: ''
    },
    validation: {
      type: Array,
      value: []
    },
    placeholder: {
      type: String,
      value: ''
    }
  },

  data() {
    return {
      error: false,
      errorMessage: [],
      validClass: ''
    }
  },

  methods: {
    checkValidation: async function ($e) {
      try {
        const success = await axios.post('/api/formvalidation', {
          validation: this.validation,
          key: this.name,
          value: $e.target.value
        });
        if (success.status == 200) {
          this.error = false
          this.validClass = 'is-valid'
        }
      } catch (e) {
        if (e.request.status == 422) {
          this.error = true
          this.errorMessage = JSON.parse(e.request.response)[this.name][0]
        }
      }
    }
  },
}

</script>
