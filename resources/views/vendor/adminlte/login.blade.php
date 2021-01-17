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
            <div class="login-form login-signin">
                <!--begin::Form-->
                <form class="form" novalidate="novalidate" id="kt_login_signin_form" action="{{ url(config('adminlte.login_url', 'login')) }}" method="POST">
                    {!! csrf_field() !!}

                    <!--begin::Title-->
                    <div class="pb-13 pt-lg-0 pt-5">
                        <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Welcome to Bluecarehub</h3>
                    </div>
                    <!--begin::Title-->
                    <!--begin::Form group-->
                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        <label class="font-size-h6 font-weight-bolder text-dark">Name</label>
                        <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text" name="username" autocomplete="off" />

                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <div class="d-flex justify-content-between mt-n5">
                            <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                            <!-- <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a> -->
                        </div>
                        <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="password" name="password" autocomplete="off" />

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!--end::Form group-->
                    <!--begin::Action-->
                    <div class="pb-lg-0 pb-5">
                        <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</button>
                    </div>
                    <!--end::Action-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Signin-->
            
            <!--begin::Forgot-->
            <div class="login-form login-forgot">
                <!--begin::Form-->
                <form class="form" novalidate="novalidate" id="kt_login_forgot_form">
                    <!--begin::Title-->
                    <div class="pb-13 pt-lg-0 pt-5">
                        <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
                        <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
                    </div>
                    <!--end::Title-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group d-flex flex-wrap pb-lg-0">
                        <button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
                        <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
                    </div>
                    <!--end::Form group-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Forgot-->
        </div>
        <!--end::Content body-->
        <!--begin::Content footer-->
        <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
            <div class="text-dark-50 font-size-lg font-weight-bolder mr-10">
                <span class="mr-1"><?= date('Y'); ?>©</span>
                <a href="http://keenthemes.com/metronic" target="_blank" class="text-dark-75 text-hover-primary">Powered by Solaris Dubai</a>
            </div>
            <!-- <a href="#" class="text-primary font-weight-bolder font-size-lg">Terms</a>
            <a href="#" class="text-primary ml-5 font-weight-bolder font-size-lg">Plans</a>
            <a href="#" class="text-primary ml-5 font-weight-bolder font-size-lg">Contact Us</a> -->
        </div>
        <!--end::Content footer-->
    </div>
    <!--end::Content-->
@stop
