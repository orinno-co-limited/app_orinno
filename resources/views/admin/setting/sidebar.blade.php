<div class="col-md-12 col-lg-4 col-xxl-3">
    <div class="account-settings-leftside bg-white theme-border radius-4 p-20 mb-25">
        <div class="tenants-details-leftsidebar-wrap d-flex">
            <ul class="account-settings-menu list-group flex-row flex-lg-column flex-wrap flex-lg-nowrap">
                <li>
                    <a href="{{ route('admin.setting.general-setting') }}"
                        class="account-settings-menu-item {{ @$subGeneralSettingActiveClass }}">
                        <span class="iconify" data-icon="carbon:settings"></span>{{ __('Basic Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.color-setting') }}"
                        class="account-settings-menu-item {{ @$subColorSettingActiveClass }}">
                        <span class="iconify"
                            data-icon="fluent:color-background-24-regular"></span>{{ __('Color Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.language.index') }}"
                        class="account-settings-menu-item {{ @$subLanguageActiveClass }}">
                        <span class="iconify" data-icon="clarity:language-line"></span>{{ __('Language') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.currency.index') }}"
                        class="account-settings-menu-item {{ @$subCurrencyActiveClass }}">
                        <span class="iconify" data-icon="heroicons:currency-dollar"></span>{{ __('Currency') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSAAS') > 1)
                    <li>
                        <a href="{{ route('admin.setting.gateway.index') }}"
                            class="account-settings-menu-item {{ @$subGatewaySettingActiveClass }}">
                            <span class="iconify"
                                data-icon="fluent:payment-16-regular"></span>{{ __('Payment Gateway') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.frontend.setting') }}"
                            class="account-settings-menu-item {{ @$subFrontendSettingActiveClass }}">
                            <span class="iconify"
                                data-icon="icon-park-outline:setting-laptop"></span>{{ __('Frontend Setting') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.setting.smtp.setting') }}"
                        class="account-settings-menu-item {{ @$subSmtpSettingActiveClass }}">
                        <span class="iconify" data-icon="mdi:git-issue"></span>{{ __('SMTP Setting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.recaptcha.setting') }}"
                        class="account-settings-menu-item {{ @$subRecaptchaSettingActiveClass }}">
                        <span class="iconify" data-icon="logos:recaptcha"></span>{{ __('reCaptcha Setting') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSMS', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.sms.setting') }}"
                            class="account-settings-menu-item {{ @$subSmsSettingActiveClass }}">
                            <span class="iconify"
                                data-icon="icon-park-outline:setting-web"></span>{{ __('Sms Setting') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.reminder.setting') }}"
                            class="account-settings-menu-item {{ @$subReminderSettingActiveClass }}">
                            <span class="iconify" data-icon="carbon:reminder"></span>{{ __('Reminder Setting') }}
                        </a>
                    </li>
                @endif
                @if (isAddonInstalled('PROTYAGREEMENT', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.agreement.setting') }}"
                            class="account-settings-menu-item {{ @$subAgreementSettingActiveClass }}">
                            <span class="iconify"
                                data-icon="icon-park-outline:agreement"></span>{{ __('Agreement Setting') }}
                        </a>
                    </li>
                @endif
                @if (isAddonInstalled('PROTYTENANCY', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.tenancy.setting') }}"
                            class="account-settings-menu-item {{ @$subTenancySettingActiveClass }}">
                            <span class="iconify"
                                data-icon="material-symbols:tenancy-outline"></span>{{ __('Tenancy Setting') }}
                        </a>
                    </li>
                @endif
                @if (isAddonInstalled('PROTYLISTING', 0) > 0)
                    <li>
                        <a href="{{ route('admin.setting.listing.setting') }}"
                            class="account-settings-menu-item {{ @$subListingSettingActiveClass }}">
                            <span class="iconify" data-icon="ri:threads-fill"></span>{{ __('Listing Setting') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.map-box.setting') }}"
                            class="account-settings-menu-item {{ @$subMapBoxSettingActiveClass }}">
                            <span class="iconify" data-icon="bx:map"></span>{{ __('Mapbox Setting') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.setting.cron.setting') }}"
                        class="account-settings-menu-item {{ @$subCronSettingActiveClass }}">
                        <span class="iconify" data-icon="carbon:batch-job"></span>{{ __('Cron Setting') }}
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSAAS') > 1)
                    <li class="mt-25">
                        <b>{{ __('Landing Page Setting') }}</b>
                    </li>
                    <li>
                        <a href="{{ route('admin.home-setting.section') }}"
                            class="account-settings-menu-item {{ @$subHomeSectionSettingActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Section Show/Hide') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.feature.index') }}"
                            class="account-settings-menu-item {{ @$subFeatureActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Amazing Features') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.how-it-work.index') }}"
                            class="account-settings-menu-item {{ @$subHowItWorkActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('How It Work') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.core-page.index') }}"
                            class="account-settings-menu-item {{ @$subCorePageActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Advance Feature') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}"
                            class="account-settings-menu-item {{ @$subTestimonialsActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Testimonials') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.faq.index') }}"
                            class="account-settings-menu-item {{ @$subFaqActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Faq') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.categories.index') }}"
                           class="account-settings-menu-item {{ @$subBlogCategoryActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Blog Category') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.index') }}"
                           class="account-settings-menu-item {{ @$subBlogActiveClass }}">
                            <span class="iconify" data-icon="carbon:settings"></span>{{ __('Blog') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
