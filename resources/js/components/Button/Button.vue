<template>
  <div>
    <button @click="clicked" :type="type" class="btn" :class="`float-${float} btn-${color}`" :disabled="loadingState"> <div v-if="!loadingState"><i :class="icon"></i> <span class="mr-1"></span>{{ text }}</div>
      <div v-if="loadingState"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> {{ loadingText }}</div>
    </button>
  </div>
</template>

<script>
export default {
  name: 'l-button',

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
      loadingState : this.loading
    }
  },

  methods: {
    clicked() {
      if (this.type != 'submit') {
        this.loadingState = true
        window.location.href = this.to
      }else{
        return false;
      }
    },
  },

  mounted() {
  }
}

</script>
