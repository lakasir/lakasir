<div>
  @isset ($topDiv)
  {{ $topDiv }}
  @endisset
  <div class="card">
    <div class="card-header">
      {{ $title }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="{{ $resources }}-table">
          <thead>
            <tr>
              @if (!$withoutCheckbox)
                @include('partials.table.select-all')
              @endif
              {{ $thead }}
              @if (!$withoutTime)
                <th> {{ __('app.global.created_at') }}</th>
              @endif
              @if (!$withoutaction)
                <th></th>
              @endif
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div class="card-footer">
      {{ $title }}
    </div>
  </div>
  @isset ($bottomDiv)
  {{ $bottomDiv }}
  @endisset
</div>
