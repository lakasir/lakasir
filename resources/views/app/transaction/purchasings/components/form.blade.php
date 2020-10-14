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
      <a href="{{ route('payment_method.create') }}" class="float-right" target="_blank"> {{ __('app.payment_methods.create.title') }}</a>
      <select2
        default-value="{{ optional(optional( $data ?? '' )->paymentMethod ?? '')->id }}"
        placeholder="{{ __('app.purchasings.placeholder.payment_method') }}"
        label="{{ __('app.purchasings.column.payment_method') }}"
        name="payment_method"
        url="{{ route('payment_method.index', [
          'filter' => [
            'key' => 'visible_in->purchasing',
            'value' => 'true'
          ]
        ]) }}"
        keytext="id"
        text="name"
        old="{{ json_encode(old('payment_method')) }}"
        @error('payment_method')
        error-message="{{ $message }}"
        :error="true"
        @enderror
      ></select2>
    </div>

  </div>

  <v-text-area icon="fa-building"
               placeholder="{{ __('app.purchasings.placeholder.note') }}"
               label="{{ __('app.purchasings.column.note') }}"
               name="note" :validation="['']"
               default-value="{{ optional( $data ?? '' )->note }}"
               old="{{ old('note') }}"
               ></v-text-area>

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('supplier.create') }}" class="float-right" target="_blank"> {{ __('app.suppliers.create.title') }}</a>
      <select2
        url="{{ route('supplier.index') }}"
        keytext="id"
        text="name"
        placeholder="{{ __('app.purchasings.placeholder.supplier') }}"
        default-value="{{ optional(optional( $data ?? '' )->supplier ?? '')->id }}"
        label="{{ __('app.purchasings.column.supplier') }}"
        name="supplier_id"
        old="{{ json_encode(old('supplier_id')) }}"
        @error('supplier_id')
        error-message="{{ $message }}"
        :error="true"
        @enderror
        ></select2>
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
  @if (!isset($method))
    <v-add-item></v-add-item>
    <v-checkbox placeholder="{{ __('app.purchasings.placeholder.paid') }}"
                old="{{ old('is_paid') }}"
                name="is_paid"
                label="{{ __('app.purchasings.column.paid') }}"
                default-value="1"
                {{ optional($data ?? '')->is_paid ? 'checked' : '' }}
                ></v-checkbox>
              @endif
</x-form>
