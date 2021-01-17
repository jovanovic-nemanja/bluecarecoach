@extends('layouts.appsecond', ['menu' => 'caretaker'])

@section('content')
	@if(session('flash'))
		<div class="alert alert-primary">
			{{ session('flash') }}
		</div>
	@endif

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Title-->
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Add Care taker</h5>
                    <!--end::Title-->
                    
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-muted">Home &nbsp;</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->

                    <!--begin::Separator-->
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Search Form-->
                    <div class="d-flex align-items-center" id="kt_subheader_search">
                        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Enter caretaker details and submit</span>
                    </div>
                    <!--end::Search Form-->
                </div>
                <!--end::Details-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom card-transparent">
                    <div class="card-body p-0">
                        <!--begin::Wizard-->
                        <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
                            <!--begin::Card-->
                            <div class="card card-custom card-shadowless rounded-top-0">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <!--begin::Wizard Form-->
                                            <form class="form" id="kt_form" action="{{ route('caretaker.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="row justify-content-center">
                                                    <div class="col-xl-9">
                                                        <!--begin::Wizard Step 1-->
                                                        <div class="my-5 step" data-wizard-type="step-content" data-type-status="current">
                                                            <h5 class="text-dark font-weight-bold mb-10">Caretaker's Profile Details:</h5>
                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('profile_logo') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label text-left">Avatar</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <div class="image-input image-input-outline" id="kt_user_add_avatar">
                                                                        <div class="image-input-wrapper"></div>
                                                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                                            <input type="file" name="profile_logo" accept=".png, .jpg, .jpeg" />
                                                                            <input type="hidden" name="profile_avatar_remove" />
                                                                        </label>
                                                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('profile_logo'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('profile_logo') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->
                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('firstname') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="firstname" type="text" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('firstname'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('firstname') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('lastname') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="lastname" type="text" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('lastname'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('lastname') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('username') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">UserName</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="username" type="text" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('username'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('username') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <div class="input-group input-group-solid input-group-lg">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-at"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="email" />
                                                                    </div>
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('email'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('email') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->
                                                            
                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Phone Number</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <div class="input-group input-group-solid input-group-lg">
                                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="phone_number" placeholder="Phone Number" />
                                                                    </div>
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('phone_number'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Password</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="password" type="password" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('password'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('password') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Password Confirm</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="password_confirmation" type="password" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('password_confirmation'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->
                                                        </div>
                                                        <!--end::Wizard Step 1-->
                                                        
                                                        <!--begin::Wizard Actions-->
                                                        <div class="d-flex justify-content-between border-top pt-10 mt-15">
                                                            <div>
                                                                <button type="button" class="btn btn-success" data-wizard-type="action-submit" style="display: initial!important;">Submit</button>

                                                                <a href="{{ route('admin.general.redirectBack') }}" class="btn btn-danger">Cancel</a>
                                                            </div>
                                                        </div>
                                                        <!--end::Wizard Actions-->
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Wizard-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
@stop

@section('script')
    <script src="{{ asset('finaldesign/assets/js/pages/custom/user/add-caretaker.js') }}"></script>
@endsection