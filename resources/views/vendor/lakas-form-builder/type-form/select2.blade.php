{{-- @dd($row, $name) --}}
<label class="row col">{{ $row['label'] }}</label>
<select class="select2{{ isset($row['add-url']) ? '-custom' : '' }} @error($name) is-invalid @enderror" name="{{ $name }}" aria-placeholder="{{ $row['placeholder'] }}">
  @foreach ($row['option'] as $option)
    <option {{ $option['value'] == (request()->input('selected') ?? old($name, $row['value'])) ? 'selected' : '' }} value="{{ $option['value'] }}">{{ $option['text'] }}</option>
  @endforeach
</select>


@push('js')
  <script>
    $(document).ready(() => {
      $(".select2-custom").select2().on('select2:open', function () {
        var a = $(this).data('select2');
        if (!$('.select2-link').length) {
          a.$results.parents('.select2-results')
            .append(`
            <div class="select2-link d-flex justify-content-center">
              <a class="btn btn-default border-0 btn-block text-primary" href="{{ $row['add-url'] ?? '' }}"><i class="fa fa-plus"></i> New item</a>
            </div>
            `)
            .on('click', function (b) {
              a.trigger('close');
              // add your code
            });
        }
      });
      $(".select2-custom").select2({
        width: "100%",
      });
    });
  </script>
@endpush
