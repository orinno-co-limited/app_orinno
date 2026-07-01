<div class="modal-header">
    <h4 class="modal-title" id="addModalLabel">{{ __('Add Permission') }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
            class="iconify" data-icon="akar-icons:cross"></span>
    </button>
</div>
<form class="ajax" action="{{route('owner.role-permission.permission-update')}}" method="post"
      data-handler="getShowMessage">
    <div class="modal-body">
        <div class="modal-inner-form-box">
            <div class="row">
                <div class="col-md-12">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('Role Name') }}</label>
                    <input type="text" value="{{$role->display_name}}" name="name" class="form-control"
                          readonly>
                    <input type="hidden" name="role_id" value="{{$role->id}}">
                </div>
                <div class="col-md-12 mt-20">
                    <div class="row">
                        <dic class="col-ms-12">
                            <h4 class="border-bottom pb-1">Permissions</h4>
                        </dic>
                        <div class="align-items-center col-md-12 d-flex flex-wrap gap-2 mt-10">
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" {{in_array($permission->name, $oldPermissions) ? 'checked' : ''}} value="{{$permission->name}}" name="permissions[]" id="permission-{{$permission->id}}">
                                    <label class="form-check-label" for="permission-{{$permission->id}}">
                                        {{$permission->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-start">
        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                title="Back">{{ __('Back') }}</button>
        <button type="submit" class="theme-btn me-3"
                title="{{ __('Submit') }}">{{ __('Submit') }}</button>
    </div>
</form>
