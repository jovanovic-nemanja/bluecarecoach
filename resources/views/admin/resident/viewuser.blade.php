@extends('layouts.appsecond', ['menu' => 'residents'])

@section('content')
	@if(session('flash'))
		<div class="alert alert-primary">
			{{ session('flash') }}
		</div>
	@endif

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-3 py-lg-8 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center mr-1">
                    <!--begin::Mobile Toggle-->
                    <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                        <span></span>
                    </button>
                    <!--end::Mobile Toggle-->
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Profile </h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-muted">Home</a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Profile 4-->
                <div class="d-flex flex-row">
                    <!--begin::Aside-->
                    <div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b">
                            <!--begin::Body-->
                            <div class="card-body pt-4">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown dropdown-inline">
                                        <br>
                                    </div>
                                </div>
                                <!--end::Toolbar-->
                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                        @if($user->profile_logo)
                                            <div class="symbol-label" style="background-image:url('{{ asset('uploads/').'/'.$user->profile_logo }}')"></div>
                                            <i class="symbol-badge bg-success"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <a class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $user->firstname." ".$user->lastname }}</a>
                                        <div class="text-muted">Resident</div>
                                        <div class="mt-2">
                                            
                                        </div>
                                    </div>
                                </div>
                                <!--end::User-->
                                <!--begin::Contact-->
                                <div class="pt-8 pb-6">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Email:</span>
                                        <a class="text-muted text-hover-primary custom_a_tag">{{ $user->email }}</a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Phone:</span>
                                        <span class="text-muted">{{ $user->phone_number }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="font-weight-bold mr-2">Location:</span>
                                        <span class="text-muted">{{ $user->street1 }}<br>{{ $user->street2 }}<br>{{ $user->city }}<br>{{ $user->zip_code }}<br>{{ $user->state }}</span>
                                    </div>
                                </div>
                                <!--end::Contact-->
                                <!--begin::Contact-->
                                <?php ($user->gender == 1) ? $gender = "Female" : $gender = "Male"; ?>
                                <div class="pb-6"><?= $gender ?> / {{ $user->birthday }}</div>
                                <!--end::Contact-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Aside-->
                    <!--begin::Content-->
                    <div class="flex-row-fluid ml-lg-8">
                        <!--begin::Advance Table Widget 8-->
                        <div class="card card-custom gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 py-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Quick Links</span>
                                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Medication, Activity and Incidence</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-0 pb-3 row">
                                <div class="col-lg-6 col-xl-4 mb-5">
                                    <!--begin::Iconbox-->
                                    <div class="card card-custom wave wave-animate-slow wave-success mb-8 mb-lg-0">
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('usermedications.indexusermedication', $user->id) }}" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Medication</a>

                                                <a href="{{ route('usermedications.indexusermedication', $user->id) }}" class="btn btn-success custom_div_tag dashboard custom_drop_down">Routine</a>
                                                <a href="{{ route('tfgs.indextfg', $user->id) }}" class="btn btn-success dashboard custom_drop_down">PRN</a>
                                                <a href="{{ route('notifications.index') }}" class="btn btn-success dashboard custom_drop_down">Reminders</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Iconbox-->
                                </div>
                                <div class="col-lg-6 col-xl-4 mb-5">
                                    <!--begin::Iconbox-->
                                    <div class="card card-custom wave wave-animate wave-custom mb-8 mb-lg-0">
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('useractivities.indexuseractivity', $user->id) }}" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Activity</a>

                                                <a href="{{ route('useractivities.indexuseractivity', $user->id) }}" class="btn btn-custom custom_div_tag dashboard custom_drop_down">Primary ADL</a>
                                                <a href="{{ route('useractivities.indexuseractivity', $user->id) }}" class="btn btn-custom dashboard custom_drop_down">Secondary ADL</a>
                                                <a href="{{ route('notifications.index') }}" class="btn btn-custom dashboard custom_drop_down">Reminders</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Iconbox-->
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <!--begin::Iconbox-->
                                    <div class="card card-custom wave wave-animate-fast wave-warning">
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('bodyharm.indexbodyharm', $user->id) }}" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Incidence</a>

                                                <a href="#" class="btn btn-warning custom_div_tag dashboard custom_drop_down">Family Visit</a>
                                                <a href="#" class="btn btn-warning dashboard custom_drop_down">Mood Change</a>
                                                <a href="{{ route('bodyharm.indexbodyharm', $user->id) }}" class="btn btn-warning dashboard custom_drop_down">Body Harm</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Iconbox-->
                                </div>
                            </div>

                            <div class="card-body pt-0 pb-3 row">
                                <div class="col-lg-12">
                                    <!--begin::Callout-->
                                    <div class="card card-custom mb-2 bg-light-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                <div class="d-flex flex-column mr-5">
                                                    <a class="h4 text-dark text-hover-primary mb-5">Temprature</a>
                                                    <i class='fas fa-thermometer' style='font-size: 70px; color: red;'></i>
                                                </div>
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                                    <a class="btn font-weight-bolder text-uppercase py-4 px-6">{{ $vitalsign['temperature']['data'] }} <span>°F</span></a>
                                                </div>
                                            </div>
                                            <a href="{{ route('vitalsign.indexresidentvitalsign', $user->id) }}">Read more</a>
                                        </div>
                                    </div>
                                    <!--end::Callout-->
                                </div>

                                <div class="col-lg-12">
                                    <!--begin::Callout-->
                                    <div class="card card-custom mb-2 bg-light-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                <div class="d-flex flex-column mr-5">
                                                    <a class="h4 text-dark text-hover-primary mb-5">Blood pressure</a>
                                                    <i class='fas fa-signal' style='font-size: 70px; color: red;'></i>
                                                </div>
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                                    <a class="btn font-weight-bolder text-uppercase py-4 px-6">{{ $vitalsign['blood_pressure']['data'] }} <span>mmHG</span></a>
                                                </div>
                                            </div>
                                            <a href="{{ route('vitalsign.indexresidentvitalsign', $user->id) }}">Read more</a>
                                        </div>
                                    </div>
                                    <!--end::Callout-->
                                </div>

                                <div class="col-lg-12">
                                    <!--begin::Callout-->
                                    <div class="card card-custom mb-2 bg-light-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                <div class="d-flex flex-column mr-5">
                                                    <a class="h4 text-dark text-hover-primary mb-5">Heart Rate</a>
                                                    <i class='fa fa-heart' style='font-size: 70px; color: red;'></i>
                                                </div>
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                                    <a class="btn font-weight-bolder text-uppercase py-4 px-6">{{ $vitalsign['heart_rate']['data'] }} <span>Per min</span></a>
                                                </div>
                                            </div>
                                            <a href="{{ route('vitalsign.indexresidentvitalsign', $user->id) }}">Read more</a>
                                        </div>
                                    </div>
                                    <!--end::Callout-->
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Advance Table Widget 8-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Profile 4-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
@stop