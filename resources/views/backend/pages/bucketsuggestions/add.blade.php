@extends('backend.layout.app')
@section('section')

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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Buckets name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control select2" id="bucket"  name="bucket">
                                                    <option value="">Select bucket</option>
                                                    @foreach ($bucket_list as $key => $value)
                                                        <option data-buckets="{{ $value['buckets'] }}" data-volume="{{ numberformat($value['volume']) }}" value="{{ $value['id'] }}">{{ $value['buckets'] }} - {{ numberformat($value['volume']) }} Cube</option>
                                                    @endforeach
                                                </select>
                                                <span class="bucket-error help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="javascript:;" class="btn btn-primary font-weight-bolder add-bucket">
                                                    <span class="svg-icon svg-icon-md"></span>Add Bucket
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ball Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control select2" id="ball"  name="ball">
                                                    <option value="">Select ball</option>
                                                    @foreach ($ball_list as $key => $value)
                                                        <option data-ball="{{ $value['balls'] }}" data-volume="{{ numberformat($value['volume']) }}" value="{{ $value['id'] }}">{{ $value['balls'] }} - {{ numberformat($value['volume']) }} Cube</option>
                                                    @endforeach
                                                </select>
                                                <span class="ball-error help-block"></span>
                                            </div>

                                            <div class="col-md-6">
                                                <a href="javascript:;" class="btn btn-primary font-weight-bolder add-ball">
                                                    <span class="svg-icon svg-icon-md"></span>Add Ball
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="form" id="add-bucket" method="POST" action="{{ route('bucket.save-add-bucket') }}">@csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" id="remaining-space">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="card-title">
                                            <span>Buckets Details</span>
                                            <span style="float: right !important;">Buckets Volume : <span id="buckets-volume">{{numberformat(0)}}</span></span>
                                        </h5>
                                    <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="form-group" >
                                            <div class="row" id="buckets-details">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                    <h5 class="card-title">
                                        <span>Ball Details</span>
                                        <span style="float: right !important;">Ball Volume : <span id="ball-volume">{{numberformat(0)}}</span></span>
                                    </h5>
                                    <hr>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="form-group" >
                                            <div class="row" id="ball-details">
                                            </div>
                                        </div>
                                    </div>
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
