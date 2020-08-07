<template>
  <div class="form-group mb-3">
    <label for="#" class="text-muted">{{ label }}</label>
    <select class="form-control select2" :multiple="multiple">
      <slot></slot>
    </select>
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
    value: {
      type: String,
      value: "",
    },
    label: {
      type: String,
      value: "",
    },
    multiple: {
      type: Boolean,
      value: false,
    },
  },
  mounted() {
    let vm = this;
    let selectElement = this.$el.children[1];
    $(selectElement)
      // init select2
      .select2({ data: this.options })
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
