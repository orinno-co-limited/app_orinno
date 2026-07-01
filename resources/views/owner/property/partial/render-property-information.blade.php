<form class="ajax" action="{{ route('owner.property.property-information.store') }}" method="post"
    data-handler="stepChange">
    @csrf
    <input type="text" name="property_id" class="d-none" value="{{ @$property->id }}">
    <input type="text" name="property_type" class="d-none" id="property_type"
        value="{{ @$property->property_type ?? 1 }}">
    <div class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
        <div class="add-property-title border-bottom pb-25 mb-25">
            <h4>{{ __('Property Information') }}</h4>
        </div>
        <div class="select-property-box bg-white theme-border radius-4 p-20 mb-25">
            <h6 class="mb-15">{{ __('Select Property') }}</h6>
            <ul class="nav nav-tabs select-property-nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="p-0 me-4 mb-1 nav-link {{ @$property->property_type ? ($property->property_type == 1 ? 'active' : '') : 'active' }} select_property_type"
                        data-property_type="1" id="own-property-tab" data-bs-toggle="tab"
                        data-bs-target="#own-property-tab-pane" type="button" role="tab"
                        aria-controls="own-property-tab-pane" aria-selected="true">
                        <span class="select-property-nav-text d-flex align-items-center position-relative">
                            <span class="select-property-nav-text-box me-2"></span>{{ __('Own Property') }}
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="p-0 me-4 mb-1 nav-link {{ @$property->property_type ? ($property->property_type == 2 ? 'active' : '') : '' }} select_property_type"
                        data-property_type="2" id="lease-property-tab" data-bs-toggle="tab"
                        data-bs-target="#lease-property-tab-pane" type="button" role="tab"
                        aria-controls="lease-property-tab-pane" aria-selected="false">
                        <span class="select-property-nav-text d-flex align-items-center position-relative">
                            <span class="select-property-nav-text-box me-2"></span>{{ __('Lease Property') }}
                        </span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="add-property-inner-box bg-white theme-border radius-4 p-20">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade {{ @$property->property_type ? ($property->property_type == 1 ? 'show active' : '') : 'show active' }}"
                    id="own-property-tab-pane" role="tabpanel" aria-labelledby="own-property-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-6 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}</label>
                            <input type="text" class="form-control" name="own_property_name"
                                placeholder="{{ __('Property Name') }}"
                                value="{{ @$property->property_type ? ($property->property_type == 1 ? $property->name : '') : '' }}">
                        </div>
                        <div class="col-md-6 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Number of Units') }}</label>
                            <input type="number" min="1" class="form-control" name="own_number_of_unit"
                                value="{{ @$property->property_type ? ($property->property_type == 1 ? $property->number_of_unit : '') : '' }}"
                                placeholder="{{ __('Number of Units') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                            <textarea class="form-control" name="own_description" placeholder="{{ __('Description') }}">{{ @$property->property_type ? ($property->property_type == 1 ? $property->description : '') : '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ @$property->property_type ? ($property->property_type == 2 ? 'show active' : '') : '' }}"
                    id="lease-property-tab-pane" role="tabpanel" aria-labelledby="lease-property-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-4 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}</label>
                            <input type="text" class="form-control" name="lease_property_name"
                                placeholder="{{ __('Property Name') }}"
                                value="{{ @$property->property_type ? ($property->property_type == 2 ? @$property->name : '') : '' }}">
                        </div>
                        <div class="col-md-4 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Number of Units') }}</label>
                            <input type="number" min="1" class="form-control" name="lease_number_of_unit"
                                placeholder="{{ __('Number of Units') }}"
                                value="{{ @$property->property_type ? ($property->property_type == 2 ? $property->number_of_unit : '') : '' }}">
                        </div>
                        <div class="col-md-4 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Lease Amount') }}</label>
                            <input type="number" min="0" step="any" class="form-control"
                                name="lease_amount" value="{{ @$property->propertyDetail->lease_amount }}"
                                placeholder="{{ __('Lease Amount') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Lease Start date') }}</label>
                            <div class="custom-datepicker">
                                <div class="custom-datepicker-inner position-relative">
                                    <input type="text" class="datepicker form-control" name="lease_start_date"
                                        value="{{ @$property->propertyDetail->lease_start_date }}" autocomplete="off"
                                        placeholder="dd-mm-yy">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Lease End date') }}</label>
                            <div class="custom-datepicker">
                                <div class="custom-datepicker-inner position-relative">
                                    <input type="text" class="datepicker form-control" name="lease_end_date"
                                        value="{{ @$property->propertyDetail->lease_end_date }}" autocomplete="off"
                                        placeholder="dd-mm-yy">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                            <textarea class="form-control" name="lease_description" placeholder="{{ __('Description') }}">{{ @$property->property_type ? ($property->property_type == 2 ? $property->description : '') : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Next/Previous Button Start -->
    <button type="submit" class="action-button theme-btn mt-25">{{ __('Save & Go to Next') }}</button>
</form>
