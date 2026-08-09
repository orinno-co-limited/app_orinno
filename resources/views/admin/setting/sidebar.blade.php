<div class="col-md-12 col-lg-4 col-xxl-3">
    <div class="account-settings-leftside bg-white theme-border radius-4 p-20 mb-25">
        <div class="tenants-details-leftsidebar-wrap d-flex">
            <ul class="account-settings-menu list-group flex-row flex-lg-column flex-wrap flex-lg-nowrap">
                <li>
                    <a href="{{ route('admin.setting.general-setting') }}"
                        class="account-settings-menu-item {{ @$subGeneralSettingActiveClass }}">
                        <i class="ri-settings-line"></i>{{ __('Basic Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.color-setting') }}"
                        class="account-settings-menu-item {{ @$subColorSettingActiveClass }}">
                        <i class="ri-palette-line"></i>{{ __('Color Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.language.index') }}"
                        class="account-settings-menu-item {{ @$subLanguageActiveClass }}">
                        <i class="ri-translate"></i>{{ __('Language') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.currency.index') }}"
                        class="account-settings-menu-item {{ @$subCurrencyActiveClass }}">
                        <i class="ri-money-dollar-circle-line"></i>{{ __('Currency') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSAAS') > 1)
                    <li>
                        <a href="{{ route('admin.setting.gateway.index') }}"
                            class="account-settings-menu-item {{ @$subGatewaySettingActiveClass }}">
                            <i class="ri-bank-card-line"></i>{{ __('Payment Gateway') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.frontend.setting') }}"
                            class="account-settings-menu-item {{ @$subFrontendSettingActiveClass }}">
                            <i class="ri-computer-line"></i>{{ __('Frontend Setting') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.setting.smtp.setting') }}"
                        class="account-settings-menu-item {{ @$subSmtpSettingActiveClass }}">
                        <i class="ri-tools-line"></i>{{ __('SMTP Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.recaptcha.setting') }}"
                        class="account-settings-menu-item {{ @$subRecaptchaSettingActiveClass }}">
                        <i class="ri-shield-check-line"></i>{{ __('reCaptcha Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.sms.setting') }}"
                        class="account-settings-menu-item {{ @$subSmsSettingActiveClass }}">
                        <i class="ri-settings-3-line"></i>{{ __('Sms Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.reminder.setting') }}"
                        class="account-settings-menu-item {{ @$subReminderSettingActiveClass }}">
                        <i class="ri-alarm-line"></i>{{ __('Reminder Setting') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYAGREEMENT', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.agreement.setting') }}"
                            class="account-settings-menu-item {{ @$subAgreementSettingActiveClass }}">
                            <i class="ri-file-list-3-line"></i>{{ __('Agreement Setting') }}
                        </a>
                    </li>
                @endif
                @if (isAddonInstalled('PROTYTENANCY', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.tenancy.setting') }}"
                            class="account-settings-menu-item {{ @$subTenancySettingActiveClass }}">
                            <i class="ri-home-4-line"></i>{{ __('Tenancy Setting') }}
                        </a>
                    </li>
                @endif
                @if (isAddonInstalled('PROTYLISTING', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.listing.setting') }}"
                            class="account-settings-menu-item {{ @$subListingSettingActiveClass }}">
                            <i class="ri-home-4-line"></i>{{ __('Listing Setting') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.map-box.setting') }}"
                            class="account-settings-menu-item {{ @$subMapBoxSettingActiveClass }}">
                            <i class="ri-map-pin-line"></i>{{ __('Mapbox Setting') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.setting.cron.setting') }}"
                        class="account-settings-menu-item {{ @$subCronSettingActiveClass }}">
                        <i class="ri-time-line"></i>{{ __('Cron Setting') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSAAS') > 1)
                    <li class="mt-25">
                        <b>{{ __('Landing Page Setting') }}</b>
                    </li>
                    <li>
                        <a href="{{ route('admin.home-setting.section') }}"
                            class="account-settings-menu-item {{ @$subHomeSectionSettingActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Section Show/Hide') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.feature.index') }}"
                            class="account-settings-menu-item {{ @$subFeatureActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Amazing Features') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.how-it-work.index') }}"
                            class="account-settings-menu-item {{ @$subHowItWorkActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('How It Work') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.core-page.index') }}"
                            class="account-settings-menu-item {{ @$subCorePageActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Advance Feature') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}"
                            class="account-settings-menu-item {{ @$subTestimonialsActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Testimonials') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.faq.index') }}"
                            class="account-settings-menu-item {{ @$subFaqActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Faq') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.categories.index') }}"
                           class="account-settings-menu-item {{ @$subBlogCategoryActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Blog Category') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.index') }}"
                           class="account-settings-menu-item {{ @$subBlogActiveClass }}">
                            <i class="ri-settings-line"></i>{{ __('Blog') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
