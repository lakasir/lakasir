<template>
  <div>
    <button @click="clicked" :type="type" class="btn btn-primary" :class="`float-${float}`" :disabled="loading"> <div v-if="!loading">{{ text }} <i :class="icon"></i></div>
      <div v-if="loading"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ loadingText }}</div>
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
  },

  data() {
    return {

    }
  },

  methods: {
    clicked() {
      this.loading = true
    }
  },

  mounted() {
  }
}

</script>
