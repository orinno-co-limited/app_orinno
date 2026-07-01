<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('tenant.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ getSettingImage('app_logo') }}" alt="logo-sm-light">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ getSettingImage('app_logo') }}" alt="logo-light">
                    </span>
                </a>
            </div>
            <button type="button" class="btn-sm px-3 font-24 header-item" id="vertical-menu-btn">
                <i class="ri-indent-decrease"></i>
            </button>

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="header-item noti-icon" id="page-header-languages-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(selectedLanguage()->icon) }}" alt="{{ selectedLanguage()->name ?? 'English' }}"
                        title="{{ selectedLanguage()->name ?? 'English' }}" class="rounded-circle avatar-xs fit-image">
                </button>
                <div class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                    aria-labelledby="page-header-languages-dropdown">
                    <div>
                        @foreach (languages() as $language)
                            <a href="{{ route('local', $language->code) }}" class="dropdown-item" title="EN">
                                <div class="d-flex">
                                    <img src="{{ $language->icon }}" class="me-3 rounded-circle avatar-xs"
                                        alt="user-pic">
                                    <div class="flex-1">{{ $language->name }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="header-item noti-icon" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-2-fill"></i>
                    <span class="noti-dot pulse"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }} p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 text-start">{{ __('Notifications') }}</h5>
                            </div>
                            <div class="col-auto">
                            </div>
                        </div>
                    </div>
                    <div data-simplebar>
                        @foreach (getNotificationLimit(auth()->id()) as $notification)
                            <a href="{{ route('notification.status', $notification->id) }}" class="notification-item">
                                <div class="d-flex">
                                    <img src="{{ getFileUrl($notification->folder_name, $notification->file_name) }}"
                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                    <div class="flex-1">
                                        <h6 class="mb-1">{{ $notification->first_name }}</h6>
                                        <div class="">
                                            <p class="mb-1 {{ $notification->is_seen != ACTIVE ? 'fw-bold' : '' }}">
                                                {{ $notification->title }}</p>
                                            <p class="mb-0 font-12"><i class="mdi mdi-clock-outline"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a class="btn-sm theme-link font-size-14 d-flex align-items-center justify-content-center"
                                href="{{ route('tenant.notification') }}">
                                {{ __('See All') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle avatar-xs fit-image header-profile-user"
                        title="{{ auth()->user()->name }}" src="{{ auth()->user()->image }}"
                        alt="{{ auth()->user()->name }}">
                    <span class="d-none d-xl-inline-block ms-1 font-medium">{{ auth()->user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                    aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="{{ route('profile') }}"><i
                            class="ri-user-line align-middle me-1"></i>{{ __('Profile') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"><i
                            class="ri-shut-down-line align-middle me-1"></i> {{ __('Logout') }}</a>
                </div>
            </div>

        </div>
    </div>
</header>
