<div class="col-md-12 col-lg-12 col-xl-4 col-xxl-3">
    <div class="account-settings-leftside bg-white theme-border radius-4 p-20 mb-25">
        <div class="tenants-details-leftsidebar-wrap d-flex">
            <ul class="account-settings-menu list-group flex-row flex-xl-column flex-wrap flex-xl-nowrap">
                <li>
                    <a href="{{ route('owner.setting.gateway.index') }}"
                        class="account-settings-menu-item {{ @$subGatewaySettingActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="fluent:payment-16-regular"></span></div>
                        {{ __('Payment Gateway') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.expense-type.index') }}"
                        class="account-settings-menu-item {{ @$subExpenseTypeActiveClass }}">
                        <div class="d-flex"><i class="ri-file-list-line"></i></div>
                        {{ __('Expense Type') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.ticket-topic.index') }}"
                        class="account-settings-menu-item {{ @$subTicketTopicActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="bi:bookmark-dash"></span></div>
                        {{ __('Tickets Topic') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.tax-setting') }}"
                        class="account-settings-menu-item {{ @$subTaxSettingActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="ant-design:percentage-outlined"></span></div>
                        {{ __('Tax Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.invoice-type.index') }}"
                        class="account-settings-menu-item {{ @$subInvoiceTypeActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="vaadin:file-text-o"></span></div>
                        {{ __('Invoice Type') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.document-config.index') }}"
                        class="account-settings-menu-item {{ @$subDocumentConfigActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="carbon:cloud-satellite-config"></span></div>
                        {{ __('Document Config') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.setting.maintenance-issue.index') }}"
                        class="account-settings-menu-item {{ @$subMaintenanceIssueActiveClass }}">
                        <div class="d-flex"><span class="iconify" data-icon="mdi:git-issue"></span></div>
                        {{ __('Maintenance Issue') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
