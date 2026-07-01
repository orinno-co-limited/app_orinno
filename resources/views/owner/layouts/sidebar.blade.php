<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ route('owner.dashboard') }}">
                        <i class="ri-dashboard-line"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @can('Manage Property')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-building-line"></i>
                            <span>{{ __('Properties') }}</span>
                        </a>
                        <ul class="sub-menu {{ @$navPropertyMMShowClass }}" aria-expanded="false">
                            <li class="{{ @$subNavAllPropertyMMActiveClass }}">
                                <a href="{{ route('owner.property.allProperty') }}"
                                    class="{{ @$subNavAllPropertyActiveClass }}">{{ __('All Property') }}</a>
                            </li>
                            <li class="{{ @$subNavAllUnitMMActiveClass }}">
                                <a href="{{ route('owner.property.allUnit') }}"
                                    class="{{ @$subNavAllUnitActiveClass }}">{{ __('All Unit') }}</a>
                            </li>
                            <li class="{{ @$subNavOwnPropertyActiveClass }}">
                                <a href="{{ route('owner.property.ownProperty') }}"
                                    class="{{ @$subNavOwnPropertyActiveClass }}">{{ __('Own Property') }}</a>
                            </li>
                            <li class="{{ @$subNavLeasePropertyActiveClass }}">
                                <a href="{{ route('owner.property.leaseProperty') }}"
                                    class="{{ @$subNavLeasePropertyActiveClass }}">{{ __('Lease Property') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Tenant')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-user-3-line"></i>
                            <span>{{ __('Tenants') }}</span>
                        </a>
                        <ul class="sub-menu {{ @$navTenantMMShowClass }}" aria-expanded="false">
                            <li class="{{ @$subNavAllTenantMMActiveClass }}">
                                <a href="{{ route('owner.tenant.index', ['type' => 'all']) }}"
                                    class="{{ @$subNavAllTenantActiveClass }}">{{ __('All Tenants') }}</a>
                            </li>
                            <li class="{{ @$subNavTenantHistoryMMActiveClass }}">
                                <a href="{{ route('owner.tenant.index', ['type' => 'history']) }}"
                                    class="{{ @$subNavTenantHistoryActiveClass }}">{{ __('Tenant History') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Billing')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-wallet-line"></i>
                            <span>{{ __('Billing Center') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="">
                                <a href="{{ route('owner.invoice.index') }}" class="">{{ __('All Invoices') }}</a>
                            </li>
                            <li class="">
                                <a href="{{ route('owner.invoice.recurring-setting.index') }}"
                                    class="">{{ __('Recurring Setting') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Expenses')
                    <li>
                        <a href="{{ route('owner.expense.index') }}">
                            <i class="ri-file-list-line"></i>
                            <span>{{ __('Expenses') }}</span>
                        </a>
                    </li>
                @endcan
                <li>
                    <a href="{{ route('owner.chats.index') }}">
                        <i class="ri-file-list-line"></i>
                        <span>{{ __('Chats') }}</span>
                    </a>
                </li>
                @can('Manage Documents')
                    <li>
                        <a href="{{ route('owner.documents.index') }}">
                            <i class="ri-article-line"></i>
                            <span>{{ __('Documents') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Information')
                    <li>
                        <a href="{{ route('owner.information.index') }}">
                            <i class="ri-folder-info-line"></i>
                            <span>{{ __('Information') }}</span>
                        </a>
                    </li>
                @endcan
                @if (isAddonInstalled('PROTYLISTING') > 0)
                    @if (getOption('LISTING_STATUS', 0) == ACTIVE)
                        @can('Manage Listing')
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.7812 16.6094C12.7812 17.0409 12.4315 17.3906 12 17.3906H7.39062C6.95914 17.3906 6.60938 17.0409 6.60938 16.6094C6.60938 16.1779 6.95914 15.8281 7.39062 15.8281H12C12.4315 15.8281 12.7812 16.1779 12.7812 16.6094ZM22 6.69336V15.8411C22 17.0407 21.5329 18.1683 20.6847 19.0165L19.0165 20.6847C18.1683 21.5329 17.0407 22 15.8411 22H6.69336C4.09918 22 2 19.9006 2 17.3066V6.69336C2 4.09918 4.09941 2 6.69336 2H17.3066C19.9008 2 22 4.09941 22 6.69336ZM20.3327 16.6224H18.1849C17.3233 16.6224 16.6224 17.3233 16.6224 18.1849V20.3327C17.1053 20.1999 17.5479 19.9436 17.9116 19.5799L19.5798 17.9116C19.9436 17.5479 20.1999 17.1053 20.3327 16.6224ZM20.4375 6.69336C20.4375 4.96285 19.0371 3.5625 17.3066 3.5625H6.69336C4.96285 3.5625 3.5625 4.96293 3.5625 6.69336V17.3066C3.5625 19.0371 4.96293 20.4375 6.69336 20.4375H15.0599V18.1849C15.0599 16.4618 16.4618 15.0599 18.1849 15.0599H20.4375V6.69336ZM16.6094 11.2188H7.39062C6.95914 11.2188 6.60938 11.5685 6.60938 12C6.60938 12.4315 6.95914 12.7812 7.39062 12.7812H16.6094C17.0409 12.7812 17.3906 12.4315 17.3906 12C17.3906 11.5685 17.0409 11.2188 16.6094 11.2188ZM16.6094 6.60938H7.39062C6.95914 6.60938 6.60938 6.95914 6.60938 7.39062C6.60938 7.82211 6.95914 8.17188 7.39062 8.17188H16.6094C17.0409 8.17188 17.3906 7.82211 17.3906 7.39062C17.3906 6.95914 17.0409 6.60938 16.6094 6.60938Z" fill="#737C90"/>
                                        </svg>
                                    </i>
                                    <span>{{ __('My Listing') }}</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('owner.listing.create') }}">{{ __('Upload List') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('owner.listing.index') }}">{{ __('All List') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('owner.listing.contact') }}">{{ __('Contact List') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                    @endif
                @endif
                @can('Manage Maintains')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-account-circle-line"></i>
                            <span>{{ __('Maintenance') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('owner.maintainer.index') }}">{{ __('Maintenance Persons') }}</a></li>
                            <li><a
                                    href="{{ route('owner.maintenance-request.index') }}">{{ __('Maintenance Request') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @if (isAddonInstalled('PROTYSAAS') < 1 || ownerCurrentPackage(getOwnerUserId())?->ticket_support == ACTIVE)
                    @can('Manage Ticket')
                        <li>
                            <a href="{{ route('owner.ticket.index') }}">
                                <i class="ri-bookmark-2-line"></i>
                                <span>{{ __('Tickets') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @if (isAddonInstalled('PROTYSAAS') < 1 || ownerCurrentPackage(getOwnerUserId())?->notice_support == ACTIVE)
                    @can('Manage Noticeboard')
                        <li>
                            <a href="{{ route('owner.noticeboard.index') }}">
                                <i class="ri-artboard-line"></i>
                                <span>{{ __('Notice Board') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @can('Manage Report')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ri-folder-chart-line"></i>
                            <span>{{ __('Report') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('owner.reports.earning') }}">{{ __('Earning') }}</a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('owner.reports.loss-profit.by.month') }}">{{ __('Loss / Profit By Month') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('owner.reports.expenses') }}">{{ __('Expenses') }}</a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('owner.reports.lease') }}">{{ __('Lease') }}</a>
                            </li> --}}
                            <li>
                                <a href="{{ route('owner.reports.occupancy') }}">{{ __('Occupancy') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('owner.reports.maintenance') }}">{{ __('Maintenance') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('owner.reports.tenant') }}">{{ __('Tenant') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Settings')
                    <li>
                        <a href="{{ route('owner.setting.gateway.index') }}">
                            <i class="ri-settings-3-line"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Team')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.7739 18C21.5232 18 22.1192 17.5285 22.6543 16.8691C23.7498 15.5194 21.9512 14.4408 21.2652 13.9126C20.5679 13.3756 19.7893 13.0714 18.9999 13M17.9999 11C19.3806 11 20.4999 9.88071 20.4999 8.5C20.4999 7.11929 19.3806 6 17.9999 6" stroke="#737C91" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M3.2259 18C2.47659 18 1.88061 17.5285 1.34548 16.8691C0.250031 15.5194 2.04861 14.4408 2.73458 13.9126C3.43191 13.3756 4.21052 13.0714 4.99994 13M5.49994 11C4.11923 11 2.99994 9.88071 2.99994 8.5C2.99994 7.11929 4.11923 6 5.49994 6" stroke="#737C91" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M8.08368 15.1112C7.0619 15.743 4.38286 17.0331 6.01458 18.6474C6.81166 19.436 7.6994 20 8.8155 20H15.1843C16.3004 20 17.1881 19.436 17.9852 18.6474C19.6169 17.0331 16.9379 15.743 15.9161 15.1112C13.52 13.6296 10.4797 13.6296 8.08368 15.1112Z" stroke="#737C91" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.4999 7.5C15.4999 9.433 13.9329 11 11.9999 11C10.0669 11 8.49988 9.433 8.49988 7.5C8.49988 5.567 10.0669 4 11.9999 4C13.9329 4 15.4999 5.567 15.4999 7.5Z" stroke="#737C91" stroke-width="1.5"/>
                                </svg>
                            </i>
                            <span>{{ __('Manage Staff') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('owner.role-permission.role-list') }}">{{ __('Role & Permission') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('owner.team-member.index') }}">{{ __('Staff Users') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @if (isAddonInstalled('PROTYSMS') > 0)
                    @can('Manage Bulk SMS/Mail')
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="ri-mail-send-line"></i>
                                <span>{{ __('Bulk Sms/Mail') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('sms-mail.sms') }}">{{ __('Sms') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('sms-mail.mail') }}">{{ __('Email') }}</a>
                                </li>
                                @if (isAddonInstalled('PROTYSMS') > 2)
                                    <li>
                                        <a href="{{ route('sms-mail.template.index') }}">{{ __('Email Template') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcan
                @endif
                @if (isAddonInstalled('PROTYAGREEMENT') > 0)
                    @can('Manage Agreement')
                        <li>
                            <a href="{{ route('owner.agreement.index') }}">
                                <i>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.3494 2.00089C15.5344 2.00089 15.6946 2.06851 15.8298 2.20375L22.6629 9.03678C22.7981 9.17202 22.8657 9.33217 22.8657 9.51723C22.8657 9.70229 22.7981 9.86244 22.6629 9.99768L20.0684 12.5921C20.2464 12.77 20.4208 12.9409 20.5916 13.1046C20.7624 13.2683 20.9119 13.4355 21.04 13.6064C21.1681 13.7772 21.2749 13.9694 21.3603 14.1829C21.4457 14.3964 21.4884 14.6349 21.4884 14.8982C21.4884 15.183 21.435 15.4534 21.3283 15.7097C21.2215 15.9659 21.0756 16.1901 20.8905 16.3823C20.7055 16.5745 20.4884 16.7275 20.2393 16.8414C19.9901 16.9553 19.7232 17.0158 19.4385 17.0229C19.4243 17.3147 19.3638 17.5816 19.257 17.8236C19.1503 18.0656 19.0079 18.2792 18.83 18.4642C18.652 18.6493 18.4385 18.7952 18.1894 18.902C17.9402 19.0087 17.6733 19.0657 17.3886 19.0728C17.3815 19.3575 17.321 19.6244 17.2071 19.8736C17.0932 20.1227 16.9402 20.3398 16.748 20.5248C16.5558 20.7099 16.3316 20.8594 16.0754 20.9732C15.8191 21.0871 15.5487 21.1405 15.264 21.1334C15.0362 21.1334 14.8049 21.0942 14.57 21.0159C14.4917 21.2366 14.3885 21.4359 14.2604 21.6138C14.1322 21.7918 13.9757 21.9484 13.7906 22.0836C13.6055 22.2188 13.4098 22.3221 13.2034 22.3932C12.997 22.4644 12.7728 22.5 12.5307 22.5C12.3457 22.5 12.182 22.4858 12.0396 22.4573C11.8973 22.4288 11.7656 22.379 11.6446 22.3078C11.5236 22.2366 11.4026 22.1583 11.2816 22.0729C11.1606 21.9875 11.036 21.8843 10.9079 21.7633C10.6588 21.9911 10.4168 22.169 10.1819 22.2971C9.94701 22.4253 9.64806 22.4929 9.28506 22.5C9.00035 22.5 8.72987 22.4466 8.47363 22.3399C8.21739 22.2331 7.99319 22.0872 7.80101 21.9021C7.60883 21.717 7.4558 21.5 7.34191 21.2508C7.22803 21.0017 7.16753 20.7312 7.16041 20.4394C6.88282 20.4323 6.61946 20.3754 6.37034 20.2686C6.12122 20.1618 5.90413 20.0159 5.71907 19.8308C5.534 19.6458 5.38809 19.4287 5.28132 19.1796C5.17456 18.9304 5.11762 18.6671 5.1105 18.3895C4.82579 18.3824 4.56243 18.3254 4.32043 18.2187C4.07843 18.1119 3.86489 17.966 3.67983 17.7809C3.49477 17.5959 3.34886 17.3788 3.24209 17.1297C3.13532 16.8805 3.07482 16.6172 3.06059 16.3396C2.76876 16.3325 2.49829 16.272 2.24916 16.1581C2.00004 16.0442 1.78295 15.8912 1.59789 15.699C1.41283 15.5068 1.26692 15.2862 1.16015 15.037C1.05338 14.7879 1 14.5174 1 14.2256C1 13.9338 1.05338 13.6598 1.16015 13.4035C1.26692 13.1473 1.42351 12.916 1.62992 12.7095L2.76164 11.5671L1.20286 9.99768C1.06762 9.86244 1 9.70229 1 9.51723C1 9.33217 1.06762 9.17202 1.20286 9.03678L8.03589 2.20375C8.17113 2.06851 8.33128 2.00089 8.51634 2.00089C8.7014 2.00089 8.86155 2.06851 8.99679 2.20375L10.4061 3.61306C10.4986 3.58459 10.6766 3.52409 10.9399 3.43156C11.2033 3.33903 11.5129 3.23582 11.8688 3.12193C12.2247 3.00805 12.5913 2.88349 12.9685 2.74825C13.3457 2.61302 13.7087 2.49201 14.0575 2.38525C14.4063 2.27848 14.6945 2.18595 14.9223 2.10766C15.1501 2.02936 15.2924 1.99377 15.3494 2.00089ZM4.33111 12.2504C4.12469 12.2504 3.94675 12.3252 3.79727 12.4747L2.59082 13.6704C2.44134 13.8199 2.36661 14.0014 2.36661 14.2149C2.36661 14.4285 2.43778 14.6064 2.58014 14.7488C2.72249 14.8911 2.904 14.9659 3.12465 14.973C3.3453 14.973 3.5268 14.9018 3.66915 14.7595L4.87561 13.5637C5.02509 13.4142 5.09982 13.2327 5.09982 13.0192C5.09982 12.8056 5.02509 12.6241 4.87561 12.4747C4.72614 12.3252 4.54464 12.2504 4.33111 12.2504ZM6.38102 14.3004C6.28137 14.3004 6.18528 14.3182 6.09275 14.3537C6.00022 14.3893 5.9148 14.4463 5.83651 14.5246L4.64073 15.731C4.49125 15.8805 4.41652 16.062 4.41652 16.2755C4.41652 16.4819 4.49125 16.6563 4.64073 16.7987C4.7902 16.941 4.9717 17.0158 5.18523 17.0229C5.28488 17.0229 5.38097 17.0051 5.4735 16.9695C5.56603 16.9339 5.65145 16.8805 5.72974 16.8094L6.92552 15.6029C7.075 15.4534 7.14973 15.2755 7.14973 15.0691C7.14973 14.8555 7.075 14.674 6.92552 14.5246C6.77605 14.3751 6.59455 14.3004 6.38102 14.3004ZM8.43093 16.3503C8.22451 16.3503 8.04657 16.425 7.8971 16.5745L6.69064 17.7703C6.54117 17.9197 6.46643 18.1012 6.46643 18.3148C6.46643 18.5283 6.53761 18.7062 6.67996 18.8486C6.82232 18.991 7.00382 19.0657 7.22447 19.0728C7.44512 19.0728 7.62662 19.0016 7.76898 18.8593L8.97543 17.6635C9.12491 17.514 9.19964 17.3325 9.19964 17.119C9.19964 16.9055 9.12491 16.7239 8.97543 16.5745C8.82596 16.425 8.64446 16.3503 8.43093 16.3503ZM8.51634 20.3754C8.51634 20.5818 8.59108 20.7562 8.74055 20.8985C8.89002 21.0409 9.07152 21.1156 9.28506 21.1227C9.38471 21.1227 9.48079 21.1049 9.57333 21.0693C9.66586 21.0337 9.75127 20.9804 9.82956 20.9092L11.0253 19.7027C11.1748 19.5533 11.2496 19.3753 11.2496 19.1689C11.2496 18.9554 11.1748 18.7739 11.0253 18.6244C10.8759 18.4749 10.6944 18.4002 10.4808 18.4002C10.3812 18.4002 10.2851 18.418 10.1926 18.4536C10.1 18.4891 10.0146 18.5461 9.93633 18.6244L8.74055 19.8308C8.59108 19.9803 8.51634 20.1618 8.51634 20.3754ZM19.3638 15.667C19.5702 15.667 19.7481 15.5922 19.8976 15.4428C20.0471 15.2933 20.1254 15.1153 20.1325 14.9089C20.1325 14.6954 20.0578 14.5139 19.9083 14.3644L13.1607 7.60611C13.1393 7.58476 13.1037 7.56697 13.0539 7.55273C13.0041 7.5385 12.9543 7.52426 12.9044 7.51003C12.8546 7.49579 12.8012 7.48511 12.7443 7.478C12.6873 7.47088 12.6446 7.46732 12.6162 7.46732C12.4311 7.46732 12.2567 7.50291 12.093 7.57408C11.9293 7.64526 11.7834 7.74135 11.6553 7.86235C11.5271 7.98336 11.4275 8.12927 11.3563 8.3001C11.2851 8.47092 11.2496 8.64886 11.2496 8.83393C11.2496 9.21117 11.1784 9.5635 11.036 9.89091C10.8937 10.2183 10.6979 10.5102 10.4488 10.7664C10.1997 11.0226 9.91142 11.2184 9.584 11.3536C9.25659 11.4888 8.9007 11.56 8.51634 11.5671C8.23163 11.5671 7.96471 11.5138 7.71559 11.407C7.46647 11.3002 7.24938 11.1543 7.06432 10.9692C6.87926 10.7842 6.73334 10.5671 6.62658 10.318C6.51981 10.0689 6.46643 9.80194 6.46643 9.51723C6.46643 9.3464 6.48422 9.15778 6.51981 8.95137C6.5554 8.74495 6.61234 8.55989 6.69064 8.39618L8.68717 4.39245C8.75123 4.27857 8.84732 4.18604 8.97543 4.11486L8.51634 3.65577L2.6442 9.51723L4.08554 10.9479C4.40584 10.9123 4.70835 10.9337 4.99306 11.012C5.27777 11.0903 5.52689 11.2219 5.74042 11.407C5.95395 11.5921 6.12478 11.8163 6.2529 12.0796C6.38102 12.343 6.44863 12.6384 6.45575 12.9658C6.74046 12.98 7.00738 13.037 7.2565 13.1366C7.50562 13.2363 7.71915 13.3786 7.8971 13.5637C8.07504 13.7487 8.22095 13.9623 8.33484 14.2043C8.44872 14.4463 8.50566 14.7167 8.50566 15.0157C8.79037 15.0299 9.05729 15.0869 9.30641 15.1865C9.55553 15.2862 9.76906 15.4285 9.94701 15.6136C10.125 15.7986 10.2709 16.0122 10.3847 16.2542C10.4986 16.4962 10.5556 16.7667 10.5556 17.0656C10.8474 17.0798 11.1179 17.1403 11.367 17.2471C11.6161 17.3539 11.8332 17.4998 12.0183 17.6848C12.2033 17.8699 12.3492 18.0906 12.456 18.3468C12.5628 18.603 12.6162 18.8771 12.6162 19.1689C12.6162 19.4892 12.5521 19.7881 12.424 20.0657C12.2959 20.3433 12.1108 20.5853 11.8688 20.7917C11.9756 20.8914 12.0717 20.9732 12.1571 21.0373C12.2425 21.1014 12.367 21.1334 12.5307 21.1334C12.7372 21.1334 12.9151 21.0587 13.0646 20.9092C13.2141 20.7597 13.2923 20.5818 13.2995 20.3754C13.2995 20.1618 13.2247 19.9803 13.0753 19.8308L14.0362 18.8593L14.7301 19.5426C14.8938 19.692 15.0718 19.7668 15.264 19.7668C15.4704 19.7668 15.6483 19.692 15.7978 19.5426C15.9473 19.3931 16.0256 19.2152 16.0327 19.0087C16.0327 18.7952 15.9579 18.6137 15.8085 18.4642L14.602 17.2578L15.5736 16.2969L16.7694 17.4927C16.9188 17.6421 17.1003 17.7169 17.3139 17.7169C17.5274 17.7169 17.7089 17.6421 17.8584 17.4927C18.0079 17.3432 18.0826 17.1617 18.0826 16.9482C18.0826 16.7346 18.0079 16.5531 17.8584 16.4036L16.6519 15.2079L17.6235 14.2363L18.83 15.4428C18.9794 15.5922 19.1574 15.667 19.3638 15.667ZM21.2108 9.51723L15.1679 3.46359L9.78686 5.25726L7.92913 8.97272C7.89354 9.03678 7.86863 9.12575 7.85439 9.23964C7.84015 9.35352 7.83304 9.44605 7.83304 9.51723C7.83304 9.70229 7.90066 9.86244 8.03589 9.99768C8.17113 10.1329 8.33128 10.2005 8.51634 10.2005C8.70852 10.2005 8.88646 10.1649 9.05017 10.0938C9.21388 10.0226 9.35623 9.9265 9.47724 9.8055C9.59824 9.6845 9.69789 9.53858 9.77618 9.36776C9.85448 9.19693 9.89007 9.01899 9.88295 8.83393C9.88295 8.45669 9.95412 8.10436 10.0965 7.77694C10.2388 7.44952 10.4346 7.1577 10.6837 6.90146C10.9328 6.64522 11.2211 6.44948 11.5485 6.31424C11.8759 6.17901 12.2318 6.10783 12.6162 6.10071C12.73 6.10071 12.851 6.11139 12.9792 6.13274C13.1073 6.15409 13.2425 6.18257 13.3849 6.21815C13.5272 6.25374 13.6518 6.30357 13.7586 6.36763C13.8653 6.43169 13.9685 6.50642 14.0682 6.59184L19.1075 11.6312L21.2108 9.51723Z" fill="#737C90"/>
                                    </svg>
                                </i>
                                <span>{{ __('Agreement') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @if (isAddonInstalled('PROTYTENANCY') > 0)
                    @if (env('TENANCY_STATUS') == ACTIVE)
                        @can('Manage Domain Config')
                            <li>
                                <a href="{{ route('owner.domain.index') }}">
                                    <i class="ri-cloud-line"></i>
                                    <span>{{ __('Domain Config') }}</span>
                                </a>
                            </li>
                        @endcan
                    @endif
                @endif
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
                @if (isAddonInstalled('PROTYSAAS') > 1)
                    @can('Manage Subscription')
                        <li>
                            <a href="{{ route('owner.subscription.index') }}">
                                <i class="ri-exchange-dollar-fill"></i>
                                <span>{{ __('My Subscription') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
