@extends('adminlte::page')

@section('content')
  <div class="row">
    <div class="col-md-3">
      @include('app.user.profiles.components.image')
      @include('app.user.profiles.components.about')
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab"> {{ __('app.profiles.settings') }}</a></li>
            @if (app()->environment('local', 'stagging'))
              <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab"> {{ __('app.profiles.activity') }}</a></li>
            @endif
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="active tab-pane" id="settings">
              @include('app.user.profiles.components.tab.setting')
            </div>
            <!-- /.tab-pane -->
            @if (app()->environment('local', 'stagging'))
              <div class="tab-pane" id="activity">
                @include('app.user.profiles.components.tab.activity')
              </div>
            @endif
            <!-- /.tab-pane -->

          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
@endsection
