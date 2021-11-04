
<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

        </div>
        <!--end::Header Menu Wrapper-->
        <!--begin::Topbar-->
        <div class="topbar">

            <!--begin::User Information-->
            <div class="dropdown">
                <!--begin::User Toggle-->
                <div class="topbar-item" data-toggle="dropdown">
                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2">
                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ optional($this->currentAdmin)->name }}</span>
                        <span class="symbol symbol-circle symbol-lg-35 symbol-25 mr-2">
						    <img alt="Admin Avatar" src="{{ asset('cms-assets/media/users/default-cms-admin.png') }}">
                        </span>
                    </div>
                </div>
                <!--end::User Toggle-->
                <!--begin::Dropdown-->
                <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                    <!--begin::Nav-->
                    <ul class="navi navi-hover py-4">
                        <!--begin::Item-->
                        <li class="navi-item">
                            <a href="{{ route('cms.current-admin.profile') }}" class="navi-link">
                                <span class="symbol symbol-20 mr-3">
                                    <i class="fa fa-id-card"></i>
                                </span>
                                <span class="navi-text">Update Profile</span>
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="navi-item active">
                            <a href="{{ route('cms.current-admin.logout') }}" class="navi-link">
                                <span class="symbol symbol-20 mr-3">
                                    <i class="fa fa-sign-out-alt"></i>
                                </span>
                                <span class="navi-text">Sign Out</span>
                            </a>
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Nav-->
                </div>
                <!--end::Dropdown-->
            </div>
            <!--end::User Information-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
