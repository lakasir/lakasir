@extends(config('hascrudactions.wrapper.layouts'))

@section(config('hascrudactions.wrapper.section'))
  @if (config('lakasir.index-style') == 'table')
    @include('app.user.components.table')
  @endif
  @if (config('lakasir.index-style') == 'grid')
    <table class="">
      <div class="row">
        @foreach ($users as $user)
          <div class="col-xs-12 col-md-4 odd" role="row">
            <div class="card">
              <div class="card-header">
                <h5 class="text-primary">{{ $user->username }}</h5>
                <span class="text-muted">@lang('app.global.created_at'): {{ $user->created_at->diffForHumans() }}</span>
              </div>
              <div class="card-body">
                @foreach ($user->showColumns() as $column => $schema)
                  <p><span>{{ $schema['label'] }}</span>: {{ $user->{$column} }}</p>
                @endforeach
              </div>
              <div class="card-footer">
                @include('partials.grid.action', [
                  'resources' => $resources,
                  'row' => $user
                ])
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="float-right">
        {{ $users->links() }}
      </div>
    </table>
  @endif
@endsection

