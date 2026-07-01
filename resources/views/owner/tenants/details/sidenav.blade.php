<ul class="account-settings-menu list-group">
    <li><a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'profile']) }}" class="account-settings-menu-item {{ @$navTenantProfileActiveClass }}"><i class="ri-account-circle-fill"></i>{{ __('Profile Information') }}</a></li>
    <li><a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'home']) }}" class="account-settings-menu-item {{ @$navTenantHomeActiveClass }}"><i class="ri-home-4-fill"></i>{{ __('Home Details') }}</a></li>
    <li><a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'payment']) }}" class="account-settings-menu-item {{ @$navTenantPaymentActiveClass }}"><i class="ri-bank-card-fill"></i>{{ __('Payment History') }}</a></li>
    <li><a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'document']) }}" class="account-settings-menu-item {{ @$navTenantDocumentActiveClass }}"><i class="ri-file-text-fill"></i>{{ __('Documents') }}</a></li>
    @if ($tenant->status == TENANT_STATUS_CLOSE)
       <li><a href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'closing-history']) }}" class="account-settings-menu-item {{ @$navTenantClosingHistoryActiveClass }}"><i class="ri-delete-back-2-line"></i>{{ __('Closing History') }}</a></li>
    @endif
</ul>
