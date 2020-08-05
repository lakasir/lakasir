<template>
  <div>
    <select>
      <slot></slot>
    </select>
  </div>
</template>

<script>
export default {
  name: 'Select2',

  props: ['options', 'value'],
  mounted() {
    let vm = this;
    console.log(vm);
    $(this.$el)
    // init select2
      .select2({ data: this.options })
      .val(this.value)
      .trigger("change")
    // emit event on change.
      .on("change", function() {
        vm.$emit("input", this.value);
      });
  },
  watch: {
    value: function(value) {
      // update value
      $(this.$el)
        .val(value)
        .trigger("change");
    },
    options: function(options) {
      // update options
      $(this.$el)
        .empty()
        .select2({ data: options });
    }
  },
  destroyed: function() {
    $(this.$el)
      .off()
      .select2("destroy");
  }
}

</script>
