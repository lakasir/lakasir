<!-- About Me Box -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">About Me</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">

    <strong><i class="fas fa-map-marker-alt mr-1"></i> {{ __('app.profiles.column.address') }}</strong>

    <p class="text-muted">{{ optional(auth()->user()->profile ?? '')->address }}</p>

    <hr>

    <strong><i class="fas fa-phone-alt mr-1"></i> {{ __('app.profiles.column.phone') }}</strong>

    <p class="text-muted">{{ optional(auth()->user()->profile ?? '')->phone }}</p>

    <hr>

    <strong><i class="fas fa-book mr-1"></i> {{ __('app.profiles.column.bio') }}</strong>

    <p class="text-muted">{{ optional(auth()->user()->profile ?? '')->bio }}</p>


    {{-- <hr> --}}

    {{-- <strong><i class="far fa-file-alt mr-1"></i> Notes</strong> --}}

    {{-- <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> --}}
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
