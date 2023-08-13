@php
$currentRoute = Route::current()->getName();
if (!empty(Auth()->guard('admin')->user())) {
   $data = Auth()->guard('admin')->user();
}
if(file_exists( public_path().'/upload/userprofile/'.$data['userimage']) && $data['userimage'] != ''){
    $image = url("upload/userprofile/".$data['userimage']);
}else{
    $image = url("upload/userprofile/default.jpg");
}
@endphp
<!--begin::Header-->
<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

        </div>
        <!--end::Header Menu Wrapper-->
        <!--begin::Topbar-->
        <div class="topbar">
            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ $data['first_name']}} {{ $data['last_name']}}</span>
                    <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                        <div class="symbol-label" style="background-image:url({{ $image }})"></div>
                    </span>
                </div>
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->
