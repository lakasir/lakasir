<x-form :route="$route" :title="$title" :method="$method ?? null" size="10">
  <div class="row">
    <v-input icon="fa-signature"
             size="6"
             placeholder="{{ __('app.purchasings.placeholder.invoice_number') }}"
             label="{{ __('app.purchasings.column.invoice_number') }}"
             old="{{ old('invoice_number') }}"
             @error('invoice_number')
             error-message="{{ $message }}"
             :error="true"
             @enderror
             name="invoice_number"
             :validation="['']"
             default-value="{{ optional( $data ?? '' )->invoice_number }}"
             info="{{ __('app.purchasings.info.invoice_number') }}"
             ></v-input>
    <div class="col-md-6">
    <select2
      :options="{{ collect($options->get('PaymentMethod')) }}"
       default-value="{{ optional(optional( $data ?? '' )->category ?? '')->id }}"
       label="{{ __('app.purchasings.column.payment_method') }}"
       name="payment_method"
       old="{{ json_encode(old('payment_method')) }}"
       @error('payment_method')
       error-message="{{ $message }}"
       :error="true"
       @enderror
       >
       <option disabled value="0"> {{ __('app.purchasings.placeholder.payment_method') }}</option>
    </select2>
    </div>

  </div>

  <v-text-area icon="fa-building"
           placeholder="{{ __('app.purchasings.placeholder.note') }}"
           label="{{ __('app.purchasings.column.note') }}"
           name="note" :validation="['']"
           old="{{ old('note') }}"
></v-text-area>

  <div class="row">
    <div class="col-md-6">
    <select2
      :options="{{ $options->get('Supplier') }}"
       default-value="{{ optional(optional( $data ?? '' )->category ?? '')->id }}"
       label="{{ __('app.purchasings.column.supplier') }}"
       name="supplier_id"
       old="{{ json_encode(old('supplier_id')) }}"
       @error('supplier_id')
       error-message="{{ $message }}"
       :error="true"
       @enderror
       >
       <option disabled value="0"> {{ __('app.purchasings.placeholder.supplier') }}</option>
    </select2>
    </div>
    <v-date-picker icon="fa-signature"
             size="6"
             placeholder="{{ __('app.purchasings.placeholder.date') }}"
             label="{{ __('app.purchasings.column.date') }}"
             old="{{ old('date') }}"
             @error('date')
             error-message="{{ $message }}"
             :error="true"
             @enderror
             name="date"
             :validation="['']"
             default-value="{{ optional( $data ?? '' )->date }}"
             info="{{ __('app.purchasings.info.date') }}"
             ></v-date-picker>
  </div>
  <v-add-item items-options="{{ collect($options->get('Item')) }}"></v-add-item>
</x-form>
