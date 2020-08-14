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
                class="select-item"
                :id="i"
                >
                <option disabled value="0"> {{ __('app.purchasings.column.items.name') }}</option>
              </select2>
            </td>
            <td>
              <input type="number" min="1" :name="`items[${i}][qty]`" class="form-control" :id="i" @keyup="calculation"/>
            </td>
            <td class="text-right">
              <input type="number" min="1" :name="`items[${i}][initial_price]`" class="form-control" :id="i" @keyup="calculation"/>
            </td>
            <td class="text-right">
              <input type="number" min="1" :name="`items[${i}][selling_price]`" class="form-control" :id="i"/>
            </td>
            <td class="text-right">
              <input type="number" min="1" readonly class="form-control sub-total" :id="`sub-total-${i}`"/>
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
              <input type="text" readonly class="form-control total"/>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

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
    calculation(evt) {
      let index = evt.target.id;
      let initial_price = $(`input[name="items[${index}][initial_price]"]`).val();
      let qty = $(`input[name="items[${index}][qty]"]`).val();
      let selling_price = $(`input[name="items[${index}][selling_price]"]`).val();
      let totalPrice = $(`#sub-total-${index}`).val(qty * initial_price)
    },
    removeArray(i) {
      document.getElementById(i).remove();
    },
    receiveValue(value) {
      axios.get(route('api.item.show', value))
        .then(res => {
          let index = $('.select-item').last().attr('id');
          $(`#sub-total-${index}`).val(res.data?.initial_price)
          $(`input[name="items[${index}][initial_price]"]`).val(res.data?.initial_price);
          $(`input[name="items[${index}][selling_price]"]`).val(res.data?.selling_price);
        })
        .catch(err => {
          alert(err)
        });
    }
  },

  mounted() {
    this.options = JSON.parse(this.itemsOptions)
  }
}

</script>
