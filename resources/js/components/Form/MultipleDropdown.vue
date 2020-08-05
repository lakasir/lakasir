<template>
  <div>
    <div class="mb-3" :class="prepend ? 'input-group' : ''">
      <label class="text-muted" v-if="!prepend">{{ label }}</label>
      <select class="custom-select" :class="dataError ? 'is-invalid' : validClass"
              :required="required" :name="name" @blur="checkValidation">
        <option value="" :selected="!old" disabled>{{ label }}</option>
        <option v-for="list in lists" v-bind:value="list" :selected="list == selected">
          {{ list.replace(/^./, str => str.toUpperCase()) }}
        </option>
      </select>
      <div v-if="dataError" class="invalid-feedback">
        {{ dataErrorMessage }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VDropdown',

  props: {
    name: {
      type: String,
      value: ''
    },
    lists: {
      type: Array,
      value: []
    },
    required: {
      type: Boolean,
      value: true
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
    error: {
      type: Boolean,
      value: false
    },
    errorMessage: {
      type: String,
      value: null
    },
    old: {
      type: String,
      value: null
    }
  },
  data() {
    return {
      dataError: false,
      dataErrorMessage: '',
      validClass: '',
      selected: ''
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
    if(this.label) {
      this.selected = this.label
    }
    if (this.error) {
      this.dataErrorMessage = this.errorMessage
      this.dataError = this.error
    }
    if (this.old) {
      this.selected = this.old
    }
  }
}

</script>
