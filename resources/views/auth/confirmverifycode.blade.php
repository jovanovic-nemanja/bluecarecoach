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
                <form class="form" novalidate="novalidate" id="kt_login_validatecode_form" action="{{ route('validatecode') }}" method="POST">
                    {!! csrf_field() !!}
                    <h5>{{ $useremail }}</h5><br>

                    <input type="hidden" name="role" value="{{ $role }}" />
                    <input type="hidden" name="email" value="{{ $useremail }}" />

                    <!--begin::Form group-->
                    <div class="form-group">
                        <label class="font-size-h6 font-weight-bolder text-dark">Verify Code</label>
                        <input required type="text" name="verify_code" class="form-control" value="{{ $id }}" placeholder="Enter Code">
                    </div>
                    <!--end::Form group-->

                    @if ($msg)
                        <br>
                        <span class="help-block">
                            <strong>{{ $msg }}</strong><br>
                            <a href="{{ route('emailverifyforresend', ['email' => $useremail, 'role' => $role]) }}">Resend</a>
                        </span>
                        <br>
                    @endif
                    <!--begin::Action-->
                    <div class="pb-lg-0 pb-5">
                        <button type="button" id="kt_login_validate_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Submit</button>
                        <button type="button" id="kt_login_validate_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
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
    <script src="{{ asset('finaldesign/assets/js/pages/custom/login/validatecode.js') }}"></script>
@endsection