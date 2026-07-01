<!-- Choose a plan Modal Start -->
@if (isAddonInstalled('PROTYSAAS') > 1)
    <div class="modal fade big-modal" id="choosePlanModal" tabindex="-1" aria-hidden="true">
        <input type="hidden" id="chooseAPanRoute" value="{{ route('owner.subscription.get_plan') }}">
        <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('owner.subscription.get.currency') }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body">
                    <!-- Choose a plan content Start -->
                    <div class="choose-plan-area">
                        <h2 class="choose-plan-area-title text-center">{{ __('Choose A Plan') }}</h2>
                        <div class="d-flex justify-content-center align-items-center pb-30">
                            <span class="mx-3">{{ __('Monthly') }}</span>
                            <div class="payment-subscription-switch form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1"
                                    id="monthly-yearly-button">
                                <label class="form-check-label" for="monthly-yearly-button"></label>
                            </div>
                            <span class="mx-3">{{ __('Yearly') }}</span>
                        </div>
                        <div class="pricing-plan-area px-5">
                            <div class="row price-table-wrap" id="planListBlock">
                            </div>
                        </div>
                    </div>
                    <!-- Choose a plan content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Choose a plan Modal End -->

    <!-- Payment Method Modal Start -->
    <div class="modal fade big-modal" id="paymentMethodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body">
                    <!-- Choose a plan content Start -->
                    <div class="payment-method-area">
                        <h2 class="text-center payment-method-area-title">{{ __('Select Payment Method') }}</h2>
                        <div class="payment-method-wrap px-5">
                            <form class="" action="{{ route('payment.subscription.checkout') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="plan_id" name="package_id" value="">
                                <input type="hidden" id="selectGateway" name="gateway">
                                <input type="hidden" id="selectCurrency" name="currency">
                                <input type="hidden" id="duration_type" name="duration_type">
                                <input type="hidden" id="quantity" name="quantity">
                                <div class="row" id="gatewayListBlock">
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="theme-btn mb-1 w-100 payNowMaxWidth"
                                            id="payBtn">{{ __('Pay Now') }}
                                            <span class="ms-1" id="gatewayCurrencyAmount"></span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Choose a plan content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Method Modal End -->
@endif
