<select2 :options="{{ $roles }}"
        default-value="{{ optional(optional( $data ?? '' )->getRoleNames() ?? '')->first() }}"
        label="{{ __('app.user.column.role') }}"
        name="role"
        old="{{ json_encode(old('role')) }}"
        @error('role')
        error-message="{{ $message }}"
        :error="true"
      @enderror
        >
        <option disabled value="0"> {{ __('app.user.placeholder.role') }}</option>
</select2>

