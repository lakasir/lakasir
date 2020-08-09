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
            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="active tab-pane" id="settings">
              @include('app.user.profiles.components.tab.setting')
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="activity">
              @include('app.user.profiles.components.tab.activity')
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="timeline">
              @include('app.user.profiles.components.tab.timeline')
            </div>
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
