@php

$currentRoute = Route::current()->getName();
@endphp

<!--begin::Subheader-->
 <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
       <!--begin::Info-->
       <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            {{-- <h5 class="text-dark font-weight-bold my-1 mr-5">{{$header['title']}}</h5> --}}
            <!--end::Page Title-->

            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">

                @php
                $count = count($header['breadcrumb']);
                $temp = 1;
                @endphp
                @foreach($header['breadcrumb'] as $key => $value)

                    @php
                        $value = (empty($value)) ? 'javascript:;' : $value;
                    @endphp

                    @if($temp!=$count)
                        <li class="breadcrumb-item">
                            <a href="{{ $value }}" class="" style="color: #4e5161;">
                                @if($temp == 1)
                                    <i class="fa fa-home" style="color: #4e5161;"></i>&nbsp;&nbsp;&nbsp;{{ $key }}
                                @else
                                    {{ $key }}
                                @endif
                            </a>
                        </li>
                    @else
                        <li class="breadcrumb-item ">{{ $key }}</li>

                    @endif

                    @php
                        $temp = $temp+1;
                    @endphp
                @endforeach

            </ul>

        </div>
        @if ($currentRoute == "analytics-dashboard" || $currentRoute == "analytics-popular-hours" || $currentRoute == "analytics-monthly-pattern" || $currentRoute == "analytics-weekdays" ||
             $currentRoute == "sales-by-group" || $currentRoute == "sales-by-brand" || $currentRoute == "sales-by-category" || $currentRoute == "revenue-by-staff" || $currentRoute == "top-10-services" ||
             $currentRoute == "top-10-products" || $currentRoute == "sales-by-employee")
            <div style="float: right">
                <select class="form-control select2" id="branch_analytics_dashboard"  name="branch">
                    {{-- @if($project_name != 'trendsetter') --}}
                    {{-- @endif --}}
                    <option value="all">All Branches</option>
                        @foreach ($branch_list as $key => $value)
                            <option value="{{ $value['id'] }}" {{ $branch == $value['id'] ? 'selected="selected"' : '' }}>{{ $value['full_name'] }}</option>
                        @endforeach

                </select>
            </div>
        @endif

       <!--end::Info-->

    </div>
 </div>
 <!--end::Subheader-->
