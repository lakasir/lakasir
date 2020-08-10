<template>
  <div class="form-group mb-3" :class="dataError ? 'has-error' : validClass">
    <label for="#" class="text-muted">{{ label }}</label>
    <select :name="name" class="form-control select2" :multiple="multiple" selected="value">
      <slot></slot>
    </select>
    <div v-if="dataError" class="text-danger text-sm">
      {{ dataErrorMessage }}
    </div>
    <small v-if="info" class="form-text text-muted">
      {{ info }}
    </small>
  </div>
</template>

<script>
export default {
  name: "Select2",

  props: {
    options: {
      type: Array,
      value: [],
    },
    name: null,
    info : {
      type: String,
      value: ''
    },
    label: {
      type: String,
      value: "",
    },
    multiple: {
      type: Boolean,
      value: false,
    },
    defaultValue: {
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
    old: null

  },

  data() {
    return {
      dataError: false,
      dataErrorMessage: '',
      validClass: '',
      value: '',
    }
  },

  mounted() {
    if (this.error) {
      this.dataErrorMessage = this.errorMessage
      this.dataError = this.error
    }
    if (this.defaultValue) {
      let defaultValue = ''
      if (!this.multiple) {
       defaultValue = this.defaultValue
      } else {
       defaultValue = JSON.parse(this.defaultValue)
      }
      this.value = defaultValue
    }
    console.log(this.value);
    if (this.old != 'null') {
      this.value = JSON.parse(this.old)
    }
    let vm = this;
    let selectElement = this.$el.children[1];
    $(selectElement)
      // init select2
      .select2({ data: this.options, width: '100%' })
      .val(this.value)
      .trigger("change")
      // emit event on change.
      .on("change", function () {
        vm.$emit("input", this.value);
      });
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el).val(value).trigger("change");
    },
    options: function (options) {
      // update options
      $(this.$el).empty().select2({ data: options });
    },
  },
  destroyed: function () {
    $(this.$el).off().select2("destroy");
  },
};
</script>

<style>
.has-error .select2-selection {
  border-color: rgb(185, 74, 72) !important;
}
</style>
