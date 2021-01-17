@extends('layouts.appsecond', ['menu' => 'manageresident'])

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
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Edit Resident</h5>
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
                        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Enter resident details and submit</span>
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
                <div class="card card-custom">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
                            <!--begin::Wizard Form-->
                            <form class="form" id="kt_form" action="{{ route('resident.update', $resident->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="put">

                                <div class="row justify-content-center">
                                    <div class="col-xl-9">
                                        <!--begin::Wizard Step 1-->
                                        <div class="my-5 step" data-wizard-type="step-content" data-type-status="current">
                                            <h5 class="text-dark font-weight-bold mb-10">User's Profile Details:</h5>
                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('profile_logo') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label text-left">Avatar</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <?php 
                                                        if(@$resident->profile_logo) {
                                                            $path = asset('uploads/') . "/" . $resident->profile_logo;
                                                        }else{
                                                            $path = "";
                                                        }
                                                    ?>
                                                    <div class="image-input image-input-empty image-input-outline" id="kt_user_edit_avatar" style="background-image: url(<?= $path ?>);">
                                                        <div class="image-input-wrapper"></div>
                                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                            <input type="file" name="profile_logo" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="profile_avatar_remove" />
                                                        </label>
                                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                        </span>
                                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="firstname" value="{{ $resident->firstname }}" type="text" />
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
                                            <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-at"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="email" value="{{ $resident->email }}" />
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
                                            <div class="form-group row {{ $errors->has('birthday') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Date of birthday</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="birthday" type="date" data-format="mm/dd/yyyy" value="{{ $resident->birthday }}" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('birthday'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('birthday') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->
                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('gender') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Gender</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <select class="form-control form-control-solid form-control-lg" name="gender">
                                                        <option value="male" <?php if($resident->gender == 0){echo 'selected';} ?>>Male</option>
                                                        <option value="female" <?php if($resident->gender == 1){echo 'selected';} ?>>Female</option>
                                                    </select>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('gender'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->
                                            
                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Phone Number</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="phone_number" placeholder="Phone Number" value="{{ $resident->phone_number }}" />
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
                                            <div class="form-group row {{ $errors->has('street1') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <textarea class="form-control form-control-solid form-control-lg" name="street1" placeholder="Street" rows="8">{{ $resident->street1 }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('street1'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street1') }}</strong>
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
                            <!--end::Wizard Form-->
                        </div>
                    </div>
                    <!--begin::Card body-->
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
    <script src="{{ asset('finaldesign/assets/js/pages/custom/user/edit-user.js') }}"></script>
@endsection