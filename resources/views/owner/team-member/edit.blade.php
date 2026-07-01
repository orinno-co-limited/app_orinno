<div class="modal-header">
    <h4 class="modal-title" id="editTeamMemberModalLabel"><span class="modalTitle">{{ __('Edit Staff User') }}</span>
    </h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
            class="iconify" data-icon="akar-icons:cross"></span></button>
</div>
<form class="ajax" action="{{route('owner.team-member.store')}}" method="POST"
      enctype="multipart/form-data" data-handler="getShowMessage">
    <input type="hidden" id="id" name="id" value="{{$teamMember->id}}">
    <div class="modal-body">
        <!-- Modal Inner Form Box Start -->
        <div class="modal-inner-form-box">
            <div class="row">
                <div class="col-md-6 mb-25">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}</label>
                    <input type="text" name="first_name" class="form-control first_name" value="{{$teamMember->first_name}}"
                           placeholder="{{ __('First Name') }}">
                </div>
                <div class="col-md-6 mb-25">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}</label>
                    <input type="text" name="last_name" class="form-control last_name" value="{{$teamMember->last_name}}"
                           placeholder="{{ __('Last Name') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-25">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-control email" value="{{$teamMember->email}}"
                           placeholder="{{ __('Email') }}">
                </div>
                <div class="col-md-6 mb-25">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                    <input type="text" name="contact_number" class="form-control contact_number" value="{{$teamMember->contact_number}}"
                           placeholder="{{ __('Contact Number') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-25">
                    <label
                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ __('Password') }}">
                </div>
                <div class="col-md-6 mc-25">
                    <label for="" class="label-text-title color-heading font-medium mb-2">{{__('Assign Role')}}</label>
                    <div class="my-custom-select-box">
                        <select name="role"
                                class="form-control role">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ in_array($role->name,
                                        $teamMember->getRoleNames()->toArray()) ? 'selected' : '' }}>
                                    {{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mc-25">
                    <label for="" class="label-text-title color-heading font-medium mb-2">{{__('Status')}}</label>
                    <div class="my-custom-select-box">
                        <select class="form-control status" name="status">
                            <option {{ $teamMember->status == ACTIVE ? 'selected' : '' }} value="{{ACTIVE}}">{{ __('Active') }}</option>
                            <option {{ $teamMember->status == DEACTIVATE ? 'selected' : '' }} value="{{DEACTIVATE}}">{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Inner Form Box End -->
    </div>

    <div class="modal-footer justify-content-start">
        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                title="{{ __('Back') }}">{{ __('Back') }}</button>
        <button type="submit" class="theme-btn me-3"
                title="{{ __('Submit') }}">{{ __('Submit') }}</button>
    </div>
</form>
