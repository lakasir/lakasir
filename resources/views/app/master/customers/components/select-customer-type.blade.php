<a href="{{ route('customer_type.create') }}" class="float-right" target="_blank"> {{ __('app.customer_types.create.title') }}</a>
<select2
        default-value="{{ optional(optional( $data ?? '' )->customerType ?? '')->id }}"
        label="{{ __('app.customer_types.title') }}"
        placeholder="{{ __('app.customer_types.placeholder.name') }}"
        name="customer_type_id"
        url="{{ route('customer_type.index') }}"
        keytext="id"
        text="name"
        old="{{ json_encode(old('customer_type_id')) }}"
        @error('customer_type_id')
        error-message="{{ $message }}"
        :error="true"
      @enderror
        >
</select2>

