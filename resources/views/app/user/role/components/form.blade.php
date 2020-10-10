@section('adminlte_css')
<style>
.scrollable {
  overflow-y: scroll;
  min-width: 600px;
  height: 600px;
}
</style>
@endsection

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
  <h5 class="text-muted">{{ __('app.role.column.permission') }}</h5>
  <input type="checkbox" id="checkAll"/>
  <label class="form-check-label">{{ __('app.global.checkAll') }}</label>
  <div class="scrollable">
    @foreach ($permissions->toArray() as $key => $permission)
      <br>
      <label class="text-muted"> {{ $key }}</label>
      @foreach ($permission as $text)
        <div class="form-check">
          <input type="checkbox" value="{{ $text['id'] }}" name="permission_id[]" {{ optional(optional($data ?? '')->permissions ?? '')->find($text['id']) ? 'checked' : '' }}/>
          <label class="form-check-label">{{ $text['text'] }}</label>
        </div>
      @endforeach
        <hr>
    @endforeach
  </div>
</x-form>
@push('js')
  <script>
    $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
  </script>
@endpush
