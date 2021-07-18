@extends(config('hascrudactions.wrapper.layouts'))

@section(config('hascrudactions.wrapper.section'))
  @include('app.user.role.components.table')
@endsection
