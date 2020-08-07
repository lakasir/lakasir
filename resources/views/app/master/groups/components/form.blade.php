<x-form :route="$route" :title="__('app.groups.create.title')" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.groups.placeholder.name') }}"
           label="{{ __('app.groups.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
    <select2
      :options="{{ $customers }}"
      :multiple="true"
      label="{{ __('app.groups.column.customer') }}">
      <option disabled value="0">Select one</option>
    </select2>
</x-form>
