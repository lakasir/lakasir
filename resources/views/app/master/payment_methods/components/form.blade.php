<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.payment_methods.placeholder.name') }}"
           label="{{ __('app.payment_methods.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
  <v-input icon="fa-signature"
           placeholder="{{ __('app.payment_methods.placeholder.code') }}"
           label="{{ __('app.payment_methods.column.code') }}"
           old="{{ old('code') }}"
           @error('code')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="code"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->code }}"
           ></v-input>
  <label class="form-check-label" for="defaultCheck1">
    {{ __('app.payment_methods.placeholder.visible_in') }}
  </label>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="visible_in[purchasing]" value="true" id="purchasing"
    @if (isset($data)) {{ isset($data->array_visible_in['purchasing']) ? 'checked' : '' }} @endif>
    <label class="form-check-label" for="purchasing">
      {{ __('app.purchasings.title') }}
    </label>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="visible_in[selling]" value="true" id="selling"
    @if (isset($data)) {{ isset($data->array_visible_in['selling']) ? 'checked' : '' }} @endif>
    <label class="form-check-label" for="selling">
      {{ __('app.sellings.title.name') }}
    </label>
  </div>
</x-form>
