@extends('backend.layout.app')
@section('section')
@php
$currentRoute = Route::current()->getName();
if (!empty(Auth()->guard('admin')->user())) {
   $data = Auth()->guard('admin')->user();
}
@endphp
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
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
                     <form class="form" id="change-password" method="POST" action="{{ route('save-password') }}">@csrf
                        <div class="card-body">

                            <input type="hidden" value="{{ $data['id'] }}" name="editid">
                            <input type="hidden" value="{{ $data['password'] }}" name="user_old_password">

                            <div class="form-group">
                                <label>Old Password
                                <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="old_password" class="form-control" placeholder="Enter Old Password" >
                            </div>

                            <div class="form-group">
                                <label>New Password
                                <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="new_password" id="password" class="form-control" placeholder="Enter New Password" >
                            </div>

                            <div class="form-group">
                                <label>New Confirm Password
                                <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="new_confirm_password" class="form-control" placeholder="Enter Confirm Password" >
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2 submitbtn green-btn">Submit</button>
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
</div>
<!--end::Entry-->
@endsection
