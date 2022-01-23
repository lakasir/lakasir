<template>
  <div>
    <div class="form-row">
      <div class="col m-2">
        <button class="btn btn-default btn-select-supplier"><i class="far fa-user fa-2x"></i> <span class="ml-2">Select Supplier</span></button>
      </div>
      <div class="col">
        <div class="form-group text-muted">
          <label for="invoice_number-id-input">{{ __('app.purchasings.column.invoice_number') }}</label>
          <input type="text" id="invoice_number-id-input" class="form-control" :placeholder="__('app.purchasings.placeholder.invoice_number')">
          <small id="invoice_number_help" class="form-text text-muted">{{ __('app.purchasings.info.invoice_number') }}</small>
        </div>
        <div class="form-group text-muted">
          <label for="date-id-input">{{ __('app.purchasings.column.date') }}</label>
          <input type="date" id="date-id-input" class="form-control" :placeholder="__('app.purchasings.placeholder.date')">
          <small id="date_help" class="form-text text-muted">{{ __('app.purchasings.info.date') }}</small>
        </div>
      </div>
    </div>
    <div class="form-group text-muted">
      <label for="formGroupExampleInput">{{ __('app.purchasings.column.note') }}</label>
      <textarea class="form-control" :placeholder="__('app.purchasings.placeholder.note')"></textarea>
    </div>
    <div class="row">
      <div class="col">
        <h5 class="text-muted">Detail Item</h5>
      </div>
      <div class="col-md-12">
        <table class="table">
          <thead class="thead-light text-muted">
            <tr>
              <th scope="col" width="40%" class="text-left border-top-0 border-right-0 border-bottom-0">Item</th>
              <th scope="col" class="text-center border-top-0 border-right-0 border-bottom-0"></th>
              <th scope="col" class="text-center border-top-0 border-right-0 border-bottom-0">Quantity</th>
              <th scope="col" class="text-right border-top-0 border-right-0 border-bottom-0">Initial Price</th>
              <th scope="col" class="text-right border-top-0 border-right-0 border-bottom-0">Selling Price</th>
              <th scope="col" class="text-right border-top-0 border-right-0 border-bottom-0">Total Price</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Nama item</td>
              <td></td>
              <td class="pb-3 border-bottom-0 description">
                <div class="" style="">
                  <input type="text" placeholder="Quantity" class="form-control text-center">
                </div>
              </td>
              <td class="pb-3 border-bottom-0 description">
                <div class="" style="">
                  <input type="text" placeholder="Rp. 0.00" class="form-control text-right">
                </div>
              </td>
              <td class="pb-3 border-bottom-0 description">
                <div class="" style="">
                  <input type="text" placeholder="Rp. 0.00" class="form-control text-right">
                </div>
              </td>
              <td class="text-right">Rp. 20.0000,00</td>
            </tr>
            <tr>
              <td colspan="6">
                <button class="btn btn-default"><i class="fas fa-plus"></i></button>
              </td>
            </tr>
            <tr>
              <td colspan="3">
              </td>
              <td class="text-right">
                <b>Total</b>
              </td>
              <td></td>
              <td class="text-right">
                Rp. 20.0000,00
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CreatePurchasing",
  mounted() {
    console.log("From Index");
  }
}
</script>

