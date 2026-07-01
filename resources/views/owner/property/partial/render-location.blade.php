<form class="ajax" action="{{ route('owner.property.location.store') }}" method="post" data-handler="stepChange">
    @csrf
    <input type="text" name="property_id" class="d-none property_id" value="{{ $property->id }}">
    <div class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
        <div class="add-property-title border-bottom pb-25 mb-25">
            <h4>{{ __('Property Location') }}</h4>
        </div>
        <div class="add-property-inner-box bg-white theme-border radius-4 p-20">
            <div class="row">
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Country') }}</label>
                    <input type="text" name="country_id" class="form-control" placeholder="{{ __('Country') }}" value="{{ @$property->propertyDetail->country_id }}">
                </div>
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('State') }}</label>
                    <input type="text" name="state_id" class="form-control" placeholder="{{ __('State') }}" value="{{ @$property->propertyDetail->state_id }}">
                </div>
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('City') }}</label>
                    <input type="text" name="city_id" class="form-control" placeholder="{{ __('City') }}" value="{{ @$property->propertyDetail->city_id }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Zip Code') }}</label>
                    <input type="text" name="zip_code" value="{{ @$property->propertyDetail->zip_code }}"
                        class="form-control" placeholder="{{ __('Zip Code') }}">
                </div>
                <div class="col-md-8 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Address') }}</label>
                    <input type="text" name="address" value="{{ @$property->propertyDetail->address }}"
                        class="form-control" placeholder="{{ __('Address') }}">
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-12 mb-25">--}}
{{--                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Map link') }}</label>--}}
{{--                    <input type="text" name="map_link" value="{{ @$property->propertyDetail->map_link }}"--}}
{{--                        class="form-control map_link" placeholder="{{ __('Map link') }}">--}}
{{--                    <small>N.B : <a href="https://maps.google.com/"--}}
{{--                            target="_blank">{{ __('Google iframe src link') }}</a></small>--}}
{{--                </div>--}}

{{--                <div class="col-md-12">--}}
{{--                    <div class="show-map-here">--}}
{{--                        <iframe id="map_link_iframe" src="{{ @$property->propertyDetail->map_link }}"--}}
{{--                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">--}}
{{--                        </iframe>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    <!-- Next/Previous Button Start -->
    <input type="button" name="previous" class="locationBack action-button-previous theme-btn mt-25" value="{{__("Back")}}">
    <button type="submit" class="action-button theme-btn mt-25">{{ __('Save & Go to Next') }}</button>
</form>
