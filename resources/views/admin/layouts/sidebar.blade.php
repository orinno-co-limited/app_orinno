<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="ri-dashboard-line"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if (isAddonInstalled('PROTYSAAS') > 3)
                    <li>
                        <a href="{{ route('admin.packages.index') }}">
                            <i class="ri-bookmark-2-line"></i>
                            <span>{{ __('Packages') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subscriptions.orders') }}">
                            <i class="ri-list-check-2"></i>
                            <span>{{ __('All Orders') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.packages.owner') }}">
                            <i class="ri-file-list-line"></i>
                            <span>{{ __('Owner Packages') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.message.index') }}">
                            <i>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.248 4.124C16.6 3.004 15.388 2.25 14 2.25H6C3.929 2.25 2.25 3.929 2.25 6V17C2.25 17.28 2.406 17.537 2.655 17.666C2.904 17.795 3.204 17.774 3.433 17.613C3.433 17.613 4.263 17.027 4.933 16.554C5.271 16.315 5.351 15.847 5.113 15.509C4.874 15.17 4.406 15.09 4.067 15.328L3.75 15.553V6C3.75 4.757 4.757 3.75 6 3.75H14C14.833 3.75 15.56 4.203 15.949 4.876C16.157 5.234 16.616 5.356 16.974 5.149C17.333 4.942 17.455 4.483 17.248 4.124Z" fill="#737C90"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 10V16.353C6.25 17.347 6.645 18.301 7.348 19.005C8.052 19.708 9.005 20.103 10 20.103H18.349C18.401 20.103 18.451 20.119 18.493 20.149L20.567 21.613C20.796 21.774 21.096 21.795 21.345 21.666C21.594 21.537 21.75 21.28 21.75 21V10C21.75 7.929 20.071 6.25 18 6.25H10C9.005 6.25 8.052 6.645 7.348 7.348C6.645 8.052 6.25 9.005 6.25 10ZM7.75 16.353V10C7.75 9.403 7.987 8.831 8.409 8.409C8.831 7.987 9.403 7.75 10 7.75H18C19.243 7.75 20.25 8.757 20.25 10V19.553L19.358 18.923C19.063 18.715 18.711 18.603 18.349 18.603C17.169 18.603 12.947 18.603 10 18.603C9.403 18.603 8.831 18.366 8.409 17.944C7.987 17.522 7.75 16.95 7.75 16.353Z" fill="#737C90"/>
                                    <path d="M11 14.5C11.5523 14.5 12 14.0523 12 13.5C12 12.9477 11.5523 12.5 11 12.5C10.4477 12.5 10 12.9477 10 13.5C10 14.0523 10.4477 14.5 11 14.5Z" fill="#737C90"/>
                                    <path d="M14 14.5C14.5523 14.5 15 14.0523 15 13.5C15 12.9477 14.5523 12.5 14 12.5C13.4477 12.5 13 12.9477 13 13.5C13 14.0523 13.4477 14.5 14 14.5Z" fill="#737C90"/>
                                    <path d="M17 14.5C17.5523 14.5 18 14.0523 18 13.5C18 12.9477 17.5523 12.5 17 12.5C16.4477 12.5 16 12.9477 16 13.5C16 14.0523 16.4477 14.5 17 14.5Z" fill="#737C90"/>
                                </svg>
                            </i>
                            <span>{{ __('Message') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-lock-2-line"></i>
                            <span>{{ __('Manage Policy') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a
                                    href="{{ route('admin.setting.terms-conditions') }}">{{ __('Terms & Conditions') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.setting.privacy-policy') }}">{{ __('Privacy Policy') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.setting.cookie-policy') }}">{{ __('Cookie Policy') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.owner.index') }}">
                        <i class="ri-user-line"></i>
                        <span>{{ __('Owner') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.setting.general-setting') }}">
                        <i class="ri-settings-3-line"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ri-account-circle-line"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                    <ul class="sub-menu {{ @$navProfileMMShowClass }}" aria-expanded="false">
                        <li class="{{ @$subNavProfileMMActiveClass }}"><a class="{{ @$subNavProfileActiveClass }}"
                                href="{{ route('profile') }}">{{ __('My Profile') }}</a></li>
                        <li><a href="{{ route('change-password') }}">{{ __('Change Password') }}</a></li>
                    </ul>
                </li>
                <li class="{{ @$subNavVersionUpdateActiveClass }}">
                    <a href="{{ route('admin.file-version-update') }}"
                        class="{{ @$subNavVersionUpdateActiveClass ? 'active' : '' }}">
                        <i class="ri-refresh-line"></i>
                        <span>{{ __('Version Update') }}</span>
                    </a>
                </li>
                <li class="font-semi-bold mt-20 text-center text-info">
                    <a href="">
                        <span>
                            {{ __('Current Version') }} :
                        </span>
                        {{ getOption('current_version', 'v1.0') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
