<template>
  <div>
    <button @click="clicked" :type="type" class="btn" :class="`float-${float} btn-${color}`" :disabled="loading"> <div v-if="!loading"><i :class="icon"></i> <span class="mr-1"></span>{{ text }}</div>
      <div v-if="loading"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> {{ loadingText }}</div>
    </button>
  </div>
</template>

<script>
export default {
  name: 'button',

  props: {
    text: {default: 'Submit', type: String},
    icon: {default: 'fas fa-arrow-circle-right', type: String},
    type: {default: 'button', type: String},
    loading: {default: false},
    loadingText: {default: 'Loading...', type: String},
    float: {default: '', type: String},
    to: {default: '', type: String},
    color: {default: 'primary', type: String}
  },

  data() {
    return {

    }
  },

  methods: {
    clicked() {
      this.loading = true
      if (this.type != 'submit') {
        window.location.href = this.to
      }
    }
  },

  mounted() {
  }
}

</script>
