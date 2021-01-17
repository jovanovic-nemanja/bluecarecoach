@extends('layouts.applogin')

@section('content')

    <!--begin::Aside-->
    <div class="login-aside d-flex flex-column flex-row-auto">
        <!--begin::Aside Bottom-->
        <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url({{ asset('images/login_bg.jpeg') }})"></div>
        <!--end::Aside Bottom-->
    </div>
    <!--begin::Aside-->
    <!--begin::Content-->
    <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
        <!--begin::Aside Top-->
        <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15" style="padding-top: 0px !important;">
            <!--begin::Aside header-->
            <a href="{{ route('home') }}" class="text-center mb-10">
                <img src="{{ asset('images/logo1.jpg') }}" class="" alt="" style="width: 100%; height: 100%;" />
            </a>
            <!--end::Aside header-->
        </div>
        <!--end::Aside Top-->

        <!--begin::Content body-->
        <div class="d-flex flex-column-fluid flex-center">
            <!--begin::Signin-->
            <div class="login-form login-signup" style="display: block;">
                <!--begin::Form-->
                <form class="form" novalidate="novalidate" id="kt_login_register_form" enctype="multipart/form-data" action="{{ url(config('adminlte.register_url', 'register')) }}" method="POST">
                    {!! csrf_field() !!}

                    <!--begin::Group-->
                    <div class="form-group row {{ $errors->has('profile_logo') ? 'has-error' : '' }}">
                        <label class="font-size-h6 font-weight-bolder text-dark">Avatar</label>
                        <div class="">
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

                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $useremail }}" required readonly>
                    </div>

                    <input type="hidden" name="email" value="{{ $useremail }}" />

                    <!--begin::Form group-->
                    <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                        <label class="font-size-h6 font-weight-bolder text-dark">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Name*" value="{{ old('firstname') }}" required>
                        <div class="fv-plugins-message-container"></div>

                        @if ($errors->has('firstname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                        <label class="font-size-h6 font-weight-bolder text-dark">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Name*" value="{{ old('lastname') }}" required>
                        <div class="fv-plugins-message-container"></div>
                        
                        @if ($errors->has('lastname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Form group-->

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
                    <div class="form-group row {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                        <label class="col-xl-3 col-lg-3 col-form-label">Phone Number</label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control form-control-solid form-control-lg" name="phone_number" type="text" />
                        </div>

                        <div class="fv-plugins-message-container"></div>

                        @if ($errors->has('phone_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Group-->

                    <input type="hidden" name="role" id="role" value="3" />

                    <!--begin::Action-->
                    <div class="pb-lg-0 pb-5">
                        <button type="button" id="kt_login_register_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Submit</button>
                        <button type="button" id="kt_login_register_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
                        <a href="{{ url(config('adminlte.login_url', 'login')) }}" class="btn btn-light-success font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</a>
                    </div>
                    <!--end::Action-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Signin-->
        </div>
        <!--end::Content body-->
        <!--begin::Content footer-->
        <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
            <div class="text-dark-50 font-size-lg font-weight-bolder mr-10">
                <span class="mr-1"><?= date('Y'); ?>©</span>
                <a href="http://keenthemes.com/metronic" target="_blank" class="text-dark-75 text-hover-primary">Powered by Solaris Dubai</a>
            </div>
        </div>
        <!--end::Content footer-->
    </div>
    <!--end::Content-->
@stop

@section('script')
    <script src="{{ asset('finaldesign/assets/js/pages/custom/login/register.js') }}"></script>
@endsection