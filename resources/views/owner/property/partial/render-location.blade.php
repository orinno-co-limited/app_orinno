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
                    <input type="text" name="country_id" class="form-control" placeholder="{{ __('Country') }}" value="{{ @$property->propertyDetail->country_id ?? 'Uganda' }}">
                </div>
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('District') }}</label>
                    <select name="state_id" class="form-select select2-location">
                        <option value="">{{ __('Select District') }}</option>
                        @php $currentDistrict = @$property->propertyDetail->state_id; @endphp
                        @if ($currentDistrict && !in_array($currentDistrict, ugandaDistricts()))
                            <option value="{{ $currentDistrict }}" selected>{{ $currentDistrict }}</option>
                        @endif
                        @foreach (ugandaDistricts() as $district)
                            <option value="{{ $district }}" {{ $currentDistrict == $district ? 'selected' : '' }}>{{ $district }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('City / Town') }}</label>
                    <select name="city_id" class="form-select select2-location">
                        <option value="">{{ __('Select City / Town') }}</option>
                        @php $currentCity = @$property->propertyDetail->city_id; @endphp
                        @if ($currentCity && !in_array($currentCity, ugandaTowns()))
                            <option value="{{ $currentCity }}" selected>{{ $currentCity }}</option>
                        @endif
                        @foreach (ugandaTowns() as $town)
                            <option value="{{ $town }}" {{ $currentCity == $town ? 'selected' : '' }}>{{ $town }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Address') }}</label>
                    <input type="text" name="address" value="{{ @$property->propertyDetail->address }}"
                        class="form-control" placeholder="{{ __('Address') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-25">
                    <label class="label-text-title color-heading font-medium mb-2 d-flex align-items-center justify-content-between">
                        {{ __('Map link') }}
                        <button type="button" id="useCurrentLocationBtn" class="use-current-location-btn font-13">
                            <i class="ri-map-pin-user-fill"></i> {{ __('Use my current location') }}
                        </button>
                    </label>
                    <input type="text" name="map_link" value="{{ @$property->propertyDetail->map_link }}"
                        class="form-control map_link" placeholder="{{ __('Map link') }}">
                    <small id="mapLocationStatus">N.B : <a href="https://maps.google.com/"
                            target="_blank">{{ __('Google iframe src link') }}</a> {{ __('or use the button above to auto-fill from your device\'s current location.') }}</small>
                </div>

                <div class="col-md-12">
                    <div class="show-map-here">
                        <iframe id="map_link_iframe" src="{{ @$property->propertyDetail->map_link }}"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Next/Previous Button Start -->
    <input type="button" name="previous" class="locationBack action-button-previous theme-btn mt-25" value="{{__("Back")}}">
    <button type="submit" class="action-button theme-btn mt-25">{{ __('Save & Go to Next') }}</button>
</form>
