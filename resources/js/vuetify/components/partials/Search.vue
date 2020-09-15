<template>
  <div>
    <v-dialog
      v-model="dialog"
      persistent
      >
      <v-card>
        <v-text-field
          v-model="search"
          :append-icon="'mdi-magnify'"
          clear-icon="mdi-close-circle"
          clearable
          solo
          single-line
          :label="__('app.sellings.placeholder.search.item_name')"
          type="text"
          class="ma-0 pa-0"
          hide-details
          autofocus
          @click:append="save"
          @click:clear="clearMessage"
          ></v-text-field>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: '',

  props: {
    show: false
  },

  data() {
    return {
      dialog: false,
      search: ''
    }
  },

  methods: {
    ...mapActions('items', [
      'getItem',
    ]),
    save() {
      this.dialog = false
      this.$router.push({ name: 'cashier.selling', query: { search: this.search } })
      this.getItem({
        search: this.search
      })
      this.$emit('closed', this.dialog)
    },
    clearMessage() {
      this.search = ''
    }
  },

  watch: {
    show: function (show) {
      this.dialog = show;
    },
  },

  mounted() {
  }
}

</script>
