<template>
  <div>
    <button @click="clicked" :type="type" class="btn" :class="`float-${float} btn-${color}`" :disabled="loading"> <div v-if="!loading"><i :class="icon"></i> <span class="mr-1"></span>{{ text }}</div>
      <div v-if="loading"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> {{ loadingText }}</div>
    </button>
    <!-- <progress max="100" :value.prop="uploadPercentage"></progress> -->
    <input @change="onFileChange" id="fileUpload" type="file" hidden>
  </div>
</template>

<script>
export default {
  name: 'UploadFile',

  props: {
    text: {default: 'Submit', type: String},
    icon: {default: 'fas fa-arrow-circle-right', type: String},
    type: {default: 'button', type: String},
    loading: {default: false},
    loadingText: {default: 'Loading...', type: String},
    float: {default: '', type: String},
    to: {default: '', type: String},
    color: {default: 'primary', type: String},
    name: {default: '', type: String}
  },

  data() {
    return {
      uploadPercentage: 0
    }
  },

  methods: {
    clicked() {
      document.getElementById("fileUpload").click()
    },
    onFileChange(e) {
      let formData = new FormData();
      let files = e.target.files || e.dataTransfer.files;

      if (!files.length)
        return;

      let message = this.__(`app.${this.name}.success`)
      formData.append(this.name, files[0]);
      axios.post(this.to,
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          onUploadProgress: function( progressEvent ) {
            this.loading = true
            this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));
            this.loadingText  = 'Loading... '  + this.uploadPercentage
          }.bind(this)
        }
      ).then(function(data){
        alert(message)
        window.location = ''
      }).catch(function(){
        console.log('FAILURE!!');
      });
    },
  },

  mounted() {
  }
}

</script>
