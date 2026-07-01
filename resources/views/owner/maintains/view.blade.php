<div class="modal-header">
    <h4 class="modal-title">{{__('Maintenance Request View') }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
            class="iconify" data-icon="akar-icons:cross"></span></button>
</div>
<div class="modal-body">
    <div class="modal-inner-form-box">
        <div class="row">
            <div class="col-md-6 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{__('Request Id') }}</label>
                     <input type="text" class="datepicker form-control start_date"
                          name="created_date" autocomplete="off" value="{{ $maintenanceRequest->request_id }}" disabled>
            </div>
            <div class="col-md-6 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{__('Issued By') }}</label>
                <input type="text" class="datepicker form-control start_date"
                       name="created_date" autocomplete="off"
                       value="{{ $maintenanceRequest->first_name }} {{ $maintenanceRequest->last_name }}({{ $maintenanceRequest->role == USER_ROLE_OWNER ? 'Owner' :($maintenanceRequest->role == USER_ROLE_TENANT ? 'Tenant' :($maintenanceRequest->role == USER_ROLE_MAINTAINER ? 'Maintainer' :($maintenanceRequest->role == USER_ROLE_ADMIN ? 'Admin' :($maintenanceRequest->role == USER_ROLE_TEAM_MEMBER ? 'Team Member' : 'Unknown')))) }})"
                       disabled>
            </div>
            <div class="col-md-6 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{__('Property') }}</label>
                    <select class="form-select flex-shrink-0 property_id" name="property_id" disabled>
                        <option value="" selected>--{{__('Select Property') }}--</option>
                        @foreach ($properties as $property)
                            <option {{ $property->id == $maintenanceRequest->property_id ? 'selected' : '' }} value="{{ $property->id }}">{{ $property->name }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-6 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{__('Unit') }}</label>
                    <select class="form-select flex-shrink-0 unit_id" name="unit_id" disabled>
                        <option value="">--{{ __('Select Unit') }}--</option>
                        @foreach ($units as $unit)
                            <option {{ $unit->id == $maintenanceRequest->unit_id ? 'selected' : '' }} value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{ __('Issue') }}</label>
                    <select class="form-select flex-shrink-0 issue_id" name="issue_id" disabled>
                        <option value="">--{{ __('Select Issue') }}--</option>
                        @foreach ($issues as $issue)
                            <option {{ $issue->id == $maintenanceRequest->issue_id ? 'selected' : '' }} value="{{ $issue->id }}">{{ $issue->name }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-6 mb-25">
                <label class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                <select class="form-select flex-shrink-0 status" name="status" disabled>
                    <option value="1" {{ $maintenanceRequest->status == 1 ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="2" {{ $maintenanceRequest->status == 2 ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                    <option value="3" {{ $maintenanceRequest->status == 3 ? 'selected' : '' }}>{{ __('Pending') }}</option>
                </select>
            </div>
        </div>
       <div class="row">
           <div class="col-md-6 mb-25">
               <label
                   class="label-text-title color-heading font-medium mb-2">{{__('Amount') }}</label>
               <input type="text" class="datepicker form-control start_date"
                      name="created_date" autocomplete="off" value="{{ $maintenanceRequest->amount }}" disabled>
           </div>

           <div class="col-md-6 mb-25">
               <label
                   class="label-text-title color-heading font-medium mb-2">{{ __('Created Date') }}</label>
               <div class="custom-datepicker">
                   <div class="custom-datepicker-inner position-relative">
                       <input type="text" class="datepicker form-control start_date"
                              name="created_date" value="{{ $maintenanceRequest->created_at }}" disabled>
                       <i class="ri-calendar-2-line"></i>
                   </div>
               </div>
           </div>
       </div>
        <div class="row">
            <div class="col-md-12 mb-25">
                <label
                    class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                <textarea class="form-control details" name="details" placeholder="{{ __('Details') }}" disabled>{{ $maintenanceRequest->details }}</textarea>
            </div>
        </div>
    </div>
</div>
