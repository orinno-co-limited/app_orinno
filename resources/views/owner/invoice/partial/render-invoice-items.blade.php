<div class="multi-fields">
    <!-- Adding Box Start -->
    @if (count($invoiceItems) > 0)
        @foreach ($invoiceItems as $invoiceItem)
            <div class="multi-field border-bottom pb-25 mb-25">
                <!-- Modal Inner Form Box Start -->
                <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                    <input type="hidden" name="invoiceItem[id][]" value="{{ $invoiceItem->id }}">
                    <div class="row">
                        <div class="col-md-6 mb-25">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Type') }}</label>
                            <select class="form-select flex-shrink-0" name="invoiceItem[invoice_type_id][]" required>
                                <option value="">{{ __('Select Option') }}</option>
                                @foreach ($invoiceTypes as $invoiceType)
                                    <option value="{{ $invoiceType->id }}"
                                        {{ $invoiceType->id == $invoiceItem->invoice_type_id ? 'selected' : '' }}>
                                        {{ $invoiceType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Amount') }}</label>
                            <input type="text" name="invoiceItem[amount][]" value="{{ $invoiceItem->amount }}"
                                class="form-control" placeholder="{{ __('Enter Amount') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label
                                class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                            <textarea class="form-control" name="invoiceItem[description][]" placeholder="{{ __('Write details here...') }}">{{ $invoiceItem->description }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Modal Inner Form Box End -->
                <button type="button" class="remove-field red-color">{{ __('Remove') }}</button>
            </div>
        @endforeach
    @else
        <div class="multi-field border-bottom pb-25 mb-25">
            <!-- Modal Inner Form Box Start -->
            <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                <input type="hidden" name="invoiceItem[id][]" value="">
                <div class="row">
                    <div class="col-md-6 mb-25">
                        <label class="label-text-title color-heading font-medium mb-2">{{ __('Invoice Type') }}</label>
                        <select class="form-select flex-shrink-0" name="invoiceItem[invoice_type_id][]" required>
                            <option value="">{{ __('Select Option') }}</option>
                            @foreach ($invoiceTypes as $invoiceType)
                                <option value="{{ $invoiceType->id }}">{{ $invoiceType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-25">
                        <label class="label-text-title color-heading font-medium mb-2">{{ __('Amount') }}</label>
                        <input type="text" name="invoiceItem[amount][]" class="form-control"
                            placeholder="{{ __('Enter Amount') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                        <textarea class="form-control" name="invoiceItem[description][]" placeholder="{{ __('Write details here...') }}"></textarea>
                    </div>
                </div>
            </div>
            <!-- Modal Inner Form Box End -->
            <button type="button" class="remove-field red-color">{{ __('Remove') }}</button>
        </div>
    @endif
    <!-- Adding Box End -->
</div>
<button type="button" class="add-field theme-btn-purple pull-right">+ {{ __('Add Multiple Types') }}</button>
