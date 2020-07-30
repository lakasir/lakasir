<div>
  <div class="card">
    <div class="card-header">
      {{ $title }}
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="{{ $resources }}-table">
          <thead>
            <tr>
              @include('partials.table.select-all')
              {{ $thead }}
              <th> {{ __('app.global.created_at') }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div class="card-footer">
    </div>
  </div>
</div>
