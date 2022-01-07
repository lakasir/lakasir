<template>
  <div>
    <div class="form-row">
      <div class="col">
        <div class="form-group">
          <label for="invoice_number-id-input">{{ __('app.purchasings.column.invoice_number') }}</label>
          <input type="text" id="invoice_number-id-input"class="form-control" :placeholder="__('app.purchasings.placeholder.invoice_number')">
          <small id="invoice_number_help" class="form-text text-muted">{{ __('app.purchasings.info.invoice_number') }}</small>
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="date-id-input">{{ __('app.purchasings.column.date') }}</label>
          <input type="date" id="date-id-input" class="form-control" :placeholder="__('app.purchasings.placeholder.date')">
          <small id="date_help" class="form-text text-muted">{{ __('app.purchasings.info.date') }}</small>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="formGroupExampleInput">{{ __('app.purchasings.column.note') }}</label>
      <textarea class="form-control" :placeholder="__('app.purchasings.placeholder.note')"></textarea>
    </div>
  </div>
</template>

<script>
export default {
  name: "CreatePurchasing",
  mounted() {
   console.log("From Index")

  }
}

</script>

