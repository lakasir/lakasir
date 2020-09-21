<!-- Profile Image -->
<div class="card card-primary card-outline">
  <div class="card-body box-profile">
    <div class="text-center">
      <img class="img-thumbnail" width="200" height="200"
            src="{{ media(optional(optional(auth()->user()->profile)->media)->first()) }}"
            alt="User profile picture">
    </div>

    <h3 class="profile-username text-center">{{ ucwords(auth()->user()->username) }}</h3>

    <p class="text-muted text-center"> {{ ucwords(auth()->user()->getRoleNames()->first()) }}</p>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
