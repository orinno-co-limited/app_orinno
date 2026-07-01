<form class="ajax" action="{{ route('owner.property.unit.store') }}" method="post" data-handler="stepChange">
    @csrf
    <input type="hidden" name="property_id" class="d-none property_id" value="{{ $property->id }}">
    <input type="hidden" name="unit_type" class="d-none" id="unit_type" value="{{ $property->unit_type ?? 2 }}">
    <div class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
        <div class="add-property-title border-bottom pb-25 mb-25">
            <h4>{{ __('Add Unit') }}</h4>
        </div>
        <div class="add-property-inner-box bg-white theme-border radius-4 p-20">
            <div class="tab-content" id="myTab1Content">
                <div class="tab-pane fade show active" id="multi-unit-tab-pane" role="tabpanel"
                    aria-labelledby="multi-unit-tab" tabindex="0">
                    <!-- Multi field Wrapper Start -->
                    <div class="multi-field-wrapper">
                        <div class="multi-fields">
                            @if (count($propertyUnits) > 0)
                                @foreach ($propertyUnits as $key => $propertyUnit)
                                    @if ($key < $property->number_of_unit)
                                        <div class="multi-field border-bottom pb-25 mb-25">
                                            <input type="hidden" name="multiple[id][]" value="{{ $propertyUnit->id }}">
                                            <div class="row">
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                                    <input type="text" name="multiple[unit_name][]"
                                                        value="{{ $propertyUnit->unit_name }}"
                                                        class="form-control multiple-unit_name"
                                                        placeholder="{{ __('Unit Name') }}">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Bedroom') }}</label>
                                                    <input type="number" min="0" name="multiple[bedroom][]"
                                                        value="{{ $propertyUnit->bedroom ?? 0 }}"
                                                        class="form-control multiple-bedroom" placeholder="0">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Baths') }}</label>
                                                    <input type="number" min="0" name="multiple[bath][]"
                                                        value="{{ $propertyUnit->bath ?? 0 }}"
                                                        class="form-control multiple-bath" placeholder="0">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Kitchen') }}</label>
                                                    <input type="number" min="0" name="multiple[kitchen][]"
                                                        value="{{ $propertyUnit->kitchen ?? 0 }}"
                                                        class="form-control multiple-kitchen" placeholder="0">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Square Feet') }}</label>
                                                    <input type="text" name="multiple[square_feet][]"
                                                        value="{{ $propertyUnit->square_feet }}"
                                                        class="form-control multiple-square_feet"
                                                        placeholder="{{ __('Square Feet') }}">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Amenities') }}</label>
                                                    <input type="text" name="multiple[amenities][]"
                                                        value="{{ $propertyUnit->amenities }}"
                                                        class="form-control multiple-amenities"
                                                        placeholder="{{ __('Amenities') }}">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Condition') }}</label>
                                                    <input type="text" name="multiple[condition][]"
                                                        value="{{ $propertyUnit->condition }}"
                                                        class="form-control multiple-condition"
                                                        placeholder="{{ __('Condition') }}">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Parking') }}</label>
                                                    <input type="text" name="multiple[parking][]"
                                                        value="{{ $propertyUnit->parking }}"
                                                        class="form-control multiple-parking"
                                                        placeholder="{{ __('Parking') }}">
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Images') }}</label>
                                                    <input type="file" name="multiple[images][]"
                                                        class="form-control multiple-images">
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-6 mb-25">
                                                    <label
                                                        class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                                    <input type="text" name="multiple[description][]"
                                                        value="{{ $propertyUnit->description }}"
                                                        class="form-control multiple-description"
                                                        placeholder="{{ __('Description') }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <!-- Adding Box Start -->
                                @for ($i = 0; $i < $property->number_of_unit; $i++)
                                    <div class="multi-field border-bottom pb-25 mb-25">
                                        <input type="hidden" name="multiple[id][]" value="">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                                <input type="text" name="multiple[unit_name][]"
                                                    class="form-control multiple-unit_name"
                                                    placeholder="{{ __('Unit Name') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Bedroom') }}</label>
                                                <input type="number" min="0" name="multiple[bedroom][]"
                                                    value="0" class="form-control multiple-bedroom"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Baths') }}</label>
                                                <input type="number" min="0" name="multiple[bath][]"
                                                    value="0" class="form-control multiple-bath"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Kitchen') }}</label>
                                                <input type="number" min="0" name="multiple[kitchen][]"
                                                    value="0" class="form-control multiple-kitchen"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Square Feet') }}</label>
                                                <input type="text" name="multiple[square_feet][]" value=""
                                                    class="form-control multiple-square_feet"
                                                    placeholder="{{ __('Square Feet') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Amenities') }}</label>
                                                <input type="text" name="multiple[amenities][]" value=""
                                                    class="form-control multiple-amenities"
                                                    placeholder="{{ __('Amenities') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Condition') }}</label>
                                                <input type="text" name="multiple[condition][]" value=""
                                                    class="form-control multiple-condition"
                                                    placeholder="{{ __('Condition') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Parking') }}</label>
                                                <input type="text" name="multiple[parking][]" value=""
                                                    class="form-control multiple-parking"
                                                    placeholder="{{ __('Parking') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Images') }}</label>
                                                <input type="file" name="multiple[images][]"
                                                    class="form-control multiple-images">
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-xl-6 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                                <input type="text" name="multiple[description][]" value=""
                                                    class="form-control multiple-description"
                                                    placeholder="{{ __('Description') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                <!-- Adding Box End -->
                            @endif
                            @if (count($propertyUnits) > 0 && count($propertyUnits) < $property->number_of_unit)
                                @for ($i = 0; $i < $property->number_of_unit - count($propertyUnits); $i++)
                                    <div class="multi-field border-bottom pb-25 mb-25">
                                        <input type="hidden" name="multiple[id][]" value="">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}</label>
                                                <input type="text" name="multiple[unit_name][]"
                                                    class="form-control multiple-unit_name"
                                                    placeholder="{{ __('Unit Name') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Bedroom') }}</label>
                                                <input type="number" min="0" name="multiple[bedroom][]"
                                                    value="0" class="form-control multiple-bedroom"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Baths') }}</label>
                                                <input type="number" min="0" name="multiple[bath][]"
                                                    value="0" class="form-control multiple-bath"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Kitchen') }}</label>
                                                <input type="number" min="0" name="multiple[kitchen][]"
                                                    value="0" class="form-control multiple-kitchen"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Square Feet') }}</label>
                                                <input type="text" name="multiple[square_feet][]" value=""
                                                    class="form-control multiple-square_feet"
                                                    placeholder="{{ __('Square Feet') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Amenities') }}</label>
                                                <input type="text" name="multiple[amenities][]" value=""
                                                    class="form-control multiple-amenities"
                                                    placeholder="{{ __('Amenities') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Condition') }}</label>
                                                <input type="text" name="multiple[condition][]" value=""
                                                    class="form-control multiple-condition"
                                                    placeholder="{{ __('Condition') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Parking') }}</label>
                                                <input type="text" name="multiple[parking][]" value=""
                                                    class="form-control multiple-parking"
                                                    placeholder="{{ __('Parking') }}">
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xl-2 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Images') }}</label>
                                                <input type="file" name="multiple[images][]"
                                                    class="form-control multiple-images">
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-xl-6 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                                <input type="text" name="multiple[description][]" value=""
                                                    class="form-control multiple-description"
                                                    placeholder="{{ __('Description') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>
                    <!-- Multi field Wrapper End -->
                </div>
            </div>
        </div>
    </div>

    <!-- Next/Previous Button Start -->
    <input type="button" name="previous" class="unitBack action-button-previous theme-btn mt-25" value="Back">
    <button type="submit" class="action-button theme-btn mt-25">{{ __('Save & Go to Next') }}</button>
</form>
