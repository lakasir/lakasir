<template>
  <div>
    <div class="row col">
      <div class="mb-3" :class="prepend ? 'input-group' : ''">
        <input type="checkbox" :class="dataError ? 'is-invalid' : validClass" :value="value"
               :name="name" v-on:focus="checkValidation" v-on:change="checkValidation" :placeholder="placeholder"aria-label="Checkbox for following text input">
        <div v-if="prepend" class="input-group-append">
          <div class="input-group-text">
            <span :class="'fas ' + icon"></span>
          </div>
        </div>
        <label class="ml-2">{{ label }}</label>
        <div v-if="dataError" class="invalid-feedback">
          {{ dataErrorMessage }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Checkbox',

  props: {
    placeholder: {default: null},
    old: {default: null},
    name: {default: null},
    icon : {
      type: String,
      value: 'fa-user'
    },
    info : {
      type: String,
      value: ''
    },
    prepend : {
      type: Boolean,
      value: false
    },
    label : {
      type: String,
      value: ''
    },
    error: {
      type: Boolean,
      value: false
    },
    errorMessage: {
      type: String,
      value: null
    },
    validation: {
      type: Array,
      value: []
    },
    defaultValue: {
      type: String,
      value: ''
    },
  },

  data() {
    return {
      dataError: false,
      dataErrorMessage: '',
      validClass: '',
      value: ''
    }
  },

  methods: {
    checkValidation: async function ($e) {
      this.value = $e.target.value
      let data = {
        validation: this.validation,
        key: this.name,
        value: $e.target.value
      }
      if (this.validation.includes('confirmation')) {
        let keyConfirmed = this.name.split('_')[0];
        let valueConfirmed = document.getElementsByName(keyConfirmed)[0].value
        data[keyConfirmed] = valueConfirmed
      }
      try {
        const success = await axios.post('/api/formvalidation', data);
        if (success.status == 200) {
          this.dataError = false
          this.validClass = 'is-valid'
        }
      } catch (e) {
        if (e.request.status == 422) {
          this.dataError = true
          this.dataErrorMessage = JSON.parse(e.request.response)[this.name][0]
        }
      }
    }
  },

  mounted() {
    if (this.error) {
      this.dataErrorMessage = this.errorMessage
      this.dataError = this.error
    }
    if (this.defaultValue) {
      this.value = this.defaultValue
    }
    if (this.old) {
      this.value = this.old
    }
  }
}

</script>
