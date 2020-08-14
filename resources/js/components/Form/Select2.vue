<template>
  <div class="form-group mb-3" :class="dataError ? 'has-error' : validClass">
    <label for="#" class="text-muted" :class="prepend ? ' d-none': ''">{{ label }}</label>
    <select :name="name" class="form-control select2" :class="addClass" :multiple="multiple" selected="value">
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
    addClass: {
      type: String,
      value: ''
    },
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
    old: null,
    prepend: false,
    getValue: false

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
        if (vm.getValue) {
          vm.$parent.receiveValue(this.value)
        }
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
