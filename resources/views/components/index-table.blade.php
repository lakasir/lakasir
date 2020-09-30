<div>
  @if ($withoutcard)
    <div class="table-responsive">
      <table class="table table-hover" id="{{ $resources }}-table">
        <thead>
          <tr>
            @if (!$withoutcheckbox)
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
        @if (isset($tbody))
          <tbody>
            {{ $tbody }}
          </tbody>
        @endif
      </table>
    </div>
  @else
    <div class="card">
      <div class="card-header">
        {{ $title }}
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="{{ $resources }}-table">
            <thead>
              <tr>
                @if (!$withoutcheckbox)
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
      </div>
    </div>
  @endif
</div>
