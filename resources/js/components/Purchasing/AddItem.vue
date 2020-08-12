<template>
  <div>
    <label class="text-muted"> {{ __('app.purchasings.column.items.header') }}</label>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th scope="col"> {{ __('app.global.action') }}</th>
            <th scope="col" width="30%"> {{ __('app.purchasings.column.items.name') }}</th>
            <th scope="col" width="10%"> {{ __('app.purchasings.column.items.qty') }}</th>
            <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.initial_price') }}</th>
            <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.selling_price') }}</th>
            <th scope="col" width="20%" class="text-right"> {{ __('app.purchasings.column.items.total') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="( item, i ) in items" :id="i">
            <td>
              <button type="button" @click="removeArray(i)" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
            </td>
            <td>
              <select2
                :get-value="true"
                :options="options"
                prepend="true"
                old="null"
                :name="`items[${i}][item_id]`"
                >
                <option disabled value="0"> {{ __('app.purchasings.column.items.name') }}</option>
              </select2>
            </td>
            <td>
              <input type="text" :name="`items[${i}][qty]`" class="form-control"/>
            </td>
            <td class="text-right">
              <input type="text" :name="`items[${i}][initial_price]`" class="form-control"/>
            </td>
            <td class="text-right">
              <input type="text" :name="`items[${i}][selling_price]`" class="form-control"/>
            </td>
            <td class="text-right">
              Rp. 10.000
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <button type="button" @click="addItem" class="btn btn-outline-success"><i class="fas fa-plus"></i></button>
            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <th class="text-right">{{ __('app.global.total') }}</th>
            <td class="text-right">
              Rp. 10.000
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AddItem',

  props: {
    itemsOptions: {
      default: '',
      type: String
    }
  },

  data() {
    return {
      items: [],
      options: []
    }
  },

  methods: {
    addItem() {
      this.items.push(this.items[this.items.length - 1] + 1)
    },
    removeArray(i) {
      document.getElementById(i).remove();
    },
    receiveValue(value) {
      console.log(value);
    }
  },

  mounted() {
    this.options = JSON.parse(this.itemsOptions)
  }
}

</script>
