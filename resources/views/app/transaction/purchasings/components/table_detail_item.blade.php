<div class="row">
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
        @each('app.transaction.purchasings.components.each', $data->purchasingDetails, 'purchasingDetail')
        <tr>
          <td colspan="4"></td>
          <th class="text-right">{{ __('app.global.total') }}</th>
          <td class="text-right">
            <b>{{ $data->total_purchasing }}</b>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@push('js')
  <script>
    $(document).ready(function() {
      $('.add').click(function(e) {
        let id = e.target.id
        $(e.target).addClass('d-none')
        // $(`#${id}`).removeClass('d-none')
        let itemName = $(`#input-item-name-${id}`)
        let itemQty = $(`#input-item-qty-${id}`)
        let initialPrice = $(`#input-initial-price-${id}`)
        let sellingPrice = $(`#input-selling-price-${id}`)
        let rowTotal = $(`#input-row-total-${id}`)

        console.log(itemName)
      })

      $('.edit').click(function(e) {
        let id = e.target.id
        $(e.target).addClass('d-none')
        $(`#add-${id}`).removeClass('d-none')
        let itemName = $(`#item-name-${id}`)
        let itemQty = $(`#item-qty-${id}`)
        let initialPrice = $(`#initial-price-${id}`)
        let sellingPrice = $(`#selling-price-${id}`)
        let rowTotal = $(`#row-total-${id}`)
        let input = (value, id, type = 'text') => {
          return `<input value="${value}" id="input-${id}" class="form-control" type="${type}">`
        }

        itemName.html(input(itemName.data('value'), $(itemName).attr('id')))
        itemQty.html(input(itemQty.data('value'), $(itemQty).attr('id'), 'number'))
        initialPrice.html(input(initialPrice.data('value'), $(initialPrice).attr('id'), 'number'))
        sellingPrice.html(input(sellingPrice.data('value'), $(sellingPrice).attr('id'), 'number'))
        rowTotal.html(input(rowTotal.data('value'), $(rowTotal).attr('id'), 'number'))
      })
    })
  </script>
@endpush
