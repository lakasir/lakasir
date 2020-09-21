<template>
  <div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">{{ title }}</h5>
          </div>
          <div class="modal-body">
            <slot></slot>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" v-if="closeable"
              @click="closed">Close</button>
            <button type="button" class="btn btn-primary" v-if="submitable"
              @click="$emit('submit')">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',

  props: {
    open: false,
    title: {
      default: 'Title Modal'
    },
    submit: {
      default: true
    },
    close: {
      default: true
    }
  },

  data() {
    return {
      submitable: false,
      closeable: false
    }
  },

  watch: {
    open: (val) => {
      let toggle = val ? 'show' : 'hide'
      $('#modal').modal(toggle)
    }
  },

  methods: {
    closed() {
      this.$emit('closed', false)
    }
  },

  mounted() {
    if (this.submit) {
      this.submitable = true
    }
    if (this.close) {
      this.closeable = true
    }
  }
}

</script>
