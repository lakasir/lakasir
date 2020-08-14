<template>
  <div>
    <div class="mb-3" :class="prepend ? 'input-group' : ''">
      <label v-if="!prepend" class="text-muted">{{ label }}</label>
      <textarea
        :type="type"
        class="form-control"
        :class="dataError ? 'is-invalid' : validClass"
        :value="value"
        :name="name"
        v-on:focus="checkValidation"
        v-on:blur="checkValidation"
        :placeholder="placeholder"
        rows="3"
      ></textarea>
      <div v-if="dataError" class="invalid-feedback">{{ dataErrorMessage }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "VTextArea",

  props: {
    name: {
      type: String,
      value: "",
    },
    type: {
      type: String,
      value: "text",
    },
    icon: {
      type: String,
      value: "fa-user",
    },
    prepend: {
      type: Boolean,
      value: false,
    },
    info: {
      type: String,
      value: "",
    },
    label: {
      type: String,
      value: "",
    },
    validation: {
      type: Array,
      value: [],
    },
    placeholder: {
      type: String,
      value: "",
    },
    defaultValue: {
      type: String,
      value: "",
    },
    error: {
      type: Boolean,
      value: false,
    },
    errorMessage: {
      type: String,
      value: null,
    },
    old: {
      type: String,
      value: null,
    },
  },

  data() {
    return {
      dataError: false,
      dataErrorMessage: "",
      validClass: "",
      value: "",
    };
  },

  methods: {
    checkValidation: async function ($e) {
      this.value = $e.target.value;
      let data = {
        validation: this.validation,
        key: this.name,
        value: $e.target.value,
      };
      if (this.validation.includes("confirmation")) {
        let keyConfirmed = this.name.split("_")[0];
        let valueConfirmed = document.getElementsByName(keyConfirmed)[0].value;
        data[keyConfirmed] = valueConfirmed;
      }
      try {
        const success = await axios.post("/api/formvalidation", data);
        if (success.status == 200) {
          this.dataError = false;
          this.validClass = "is-valid";
        }
      } catch (e) {
        if (e.request.status == 422) {
          this.dataError = true;
          this.dataErrorMessage = JSON.parse(e.request.response)[this.name][0];
        }
      }
    },
  },
  mounted() {
    if (this.error) {
      this.dataErrorMessage = this.errorMessage;
      this.dataError = this.error;
    }
    if (this.defaultValue) {
      this.value = this.defaultValue
    }
    if (this.old) {
      this.value = this.old;
    }
  },
};
</script>
