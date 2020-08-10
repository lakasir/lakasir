<x-form :route="$route" :title="$title" :method="$method ?? null">
  <v-input icon="fa-signature"
           placeholder="{{ __('app.role.placeholder.name') }}"
           label="{{ __('app.role.column.name') }}"
           old="{{ old('name') }}"
           @error('name')
           error-message="{{ $message }}"
           :error="true"
           @enderror
           name="name"
           :validation="['required']"
           default-value="{{ optional( $data ?? '' )->name }}"
           ></v-input>
  <label class="text-muted">{{ __('app.role.column.permission') }}</label>
  @foreach ($permissions->toArray() as $key => $permission)
    <br>
    <label class="text-muted"> {{ $key }}</label>
    <hr>
    @foreach ($permission as $text)
      <div class="form-check">
        <input type="checkbox" value="{{ $text['id'] }}" name="permission_id[]" {{ optional(optional($data ?? '')->permissions ?? '')->find($text['id']) ? 'checked' : '' }}/>
        <label class="form-check-label">{{ $text['text'] }}</label>
      </div>
    @endforeach
  @endforeach
</x-form>
