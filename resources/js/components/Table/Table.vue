<template>
  <div>
    <div :class="size">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            {{ title }}
          </h5>
        </div>
        <div class="card-body">
          <b-table :fields="fields"></b-table>
        </div>
        <div class="card-footer"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'table',

  props: {
    data: { default: null, type: String },
    title: { default: 'Title', type: String },
    size: { default: 'col-md-12', type: String },
    search: { default: false, type: Boolean },
    columns: { default: [], type: String }
  },

  data() {
    return {
      items: {
        default: null,
        type: Array
      },
      fields: {
        default: [],
        type: Array
      },
      rows: {
        default: null,
        type: Array
      },
      titles: [
        {
          label: 'name',
          prop: 'name'
        }
      ]
    }
  },

  methods: {

  },

  mounted() {
    let data = JSON.parse(this.data)
    let fields = []
    JSON.parse(this.columns).forEach((i, v) => {
      fields.push(i.label)
    })
    this.items = data
    console.log(fields);
    this.fields = fields
  }
}

</script>
