<div>
  <div class="form-group">
    <select class="form-control" id="bulk-action">
      <option value="" disabled selected>@lang('app.global.bulk-action')</option>
      @foreach ($getOption() as $option)
        <option data-action="{{ $option['action'] }}">{{ $option['text'] }}</option>
      @endforeach
    </select>
  </div>
</div>
