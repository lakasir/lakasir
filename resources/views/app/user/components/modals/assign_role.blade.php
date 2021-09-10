<!-- Modal -->
<div class="modal fade" id="assign-role-modal" tabindex="-1" role="dialog" aria-labelledby="assign-role-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="#" method="POST" accept-charset="utf-8" class="form-assign-role">
        <div class="modal-header">
          <h5 class="modal-title" id="assign-role-modal-label">@lang("app.user.custom_action.assign_role")</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body d-flex">
          <div class="col-md-12 p-0">
            @csrf
            @method("PUT")
            @include('app.user.components.role_select2', [
              'data' => null,
            ])
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang("app.global.cancel")</button>
          <button type="submit" class="btn btn-primary">@lang("app.global.submit")</button>
        </div>
      </form>
    </div>
  </div>
</div>
