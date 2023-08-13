@extends('backend.layout.app')
@section('section')
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
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">{{ $header['title'] }}</h3>
                    </div>
                    <!--begin::Form-->
                    <form class="form" id="update-profile" method="POST" action="{{ route('save-profile') }}" enctype="multipart/form-data">@csrf
                        <div class="card-body">


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>First Name
                                        <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control" placeholder="Enter your full name first name" value="{{ $data['first_name'] }}">

                                        <input type="hidden" name="edit_id" class="form-control" value="{{ $data['id'] }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Last Name
                                        <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control" placeholder="Enter your full name last name" value="{{ $data['last_name'] }}">
                                    </div>
                                </div>




                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email
                                        <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ $data['email'] }}">
                                    </div>
                                </div>



                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Profile Image</label>
                                        <div class="">
                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                <div class="image-input-wrapper my-avtar" style="background-image: url({{ $image }})"></div>

                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pencil  icon-sm text-muted"></i>
                                                    <input type="file" name="userimage" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2 green-btn submitbtn">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->

            </div>

        </div>
    </div>
    <!--end::Container-->
    <!--end::Container-->
</div>
<!--end::Entry-->
@endsection
