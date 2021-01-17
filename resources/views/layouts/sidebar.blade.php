<div class="aside aside-left d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu min-h-lg-800px" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                <li class="menu-section">
                    <h4 class="menu-text">Main</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>

                <li class="menu-item menu-item-submenu <?= ($menu == 'residents' || $menu == 'addresident' || $menu == 'manageresident') ? "menu-item-open" : "" ?> menu-item-here" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon svg-icon-2x custom_span_tag_flex">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Residents</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">Residents</span>
                                </span>
                            </li>
                            <li class="menu-item <?= ($menu == "residents") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                <a href="{{ route('home') }}" class="menu-link <?= ($menu == "residents") ? "menu-item-active" : "" ?>">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">All Residents</span>
                                </a>
                            </li>

                            @if(auth()->user()->hasRole('admin'))
                                <li class="menu-item <?= ($menu == "addresident") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('resident.add') }}" class="menu-link <?= ($menu == "addresident") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Add Residents</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "manageresident") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('resident.management') }}" class="menu-link <?= ($menu == "manageresident") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Manage Residents</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                @if(auth()->user()->hasRole('admin'))
                    <li class="menu-item <?= ($menu == 'caretaker') ? "menu-item-active" : "" ?>" aria-haspopup="true">
                        <a href="{{ route('caretaker.index') }}" class="menu-link">
                            <span class="svg-icon svg-icon-2x custom_span_tag_flex">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Care Takers</span>
                        </a>
                    </li>

                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{ route('home') }}" class="menu-link">
                            <span class="svg-icon svg-icon-2x custom_span_tag_flex">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Vendors</span>
                        </a>
                    </li>

                    <li class="menu-item menu-item-submenu <?= ($menu == 'activities' || $menu == 'incidences' || $menu == 'medications' || $menu == 'routes' || $menu == 'bodyharmcomments' || $menu == 'reminderconfigs' || $menu == 'adminlogs' || $menu == 'switchreminder') ? "menu-item-open" : "" ?> menu-item-here" aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Setup</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item menu-item-parent" aria-haspopup="true">
                                    <span class="menu-link">
                                        <span class="menu-text">Setup</span>
                                    </span>
                                </li>
                                <li class="menu-item <?= ($menu == "activities") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('activities.index') }}" class="menu-link <?= ($menu == "activities") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Activities</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "incidences") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('incidences.index') }}" class="menu-link <?= ($menu == "incidences") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Incidences</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "medications") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('medications.index') }}" class="menu-link <?= ($menu == "medications") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Medications</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "routes") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('routes.index') }}" class="menu-link <?= ($menu == "routes") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Routes</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "bodyharmcomments") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('bodyharmcomments.index') }}" class="menu-link <?= ($menu == "bodyharmcomments") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Body harm Comments</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "reminderconfigs") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('reminderconfigs.index') }}" class="menu-link <?= ($menu == "reminderconfigs") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Reminder Configs</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "switchreminder") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('switchreminder.index') }}" class="menu-link <?= ($menu == "switchreminder") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Reminder Disable</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= ($menu == "adminlogs") ? "menu-item-active" : "" ?>" aria-haspopup="true">
                                    <a href="{{ route('adminlogs.index') }}" class="menu-link <?= ($menu == "adminlogs") ? "menu-item-active" : "" ?>">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Admin Logs</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>