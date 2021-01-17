@extends('layouts.appsecond', ['menu' => 'addresident'])

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
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">New Resident</h5>
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
                <div class="card card-custom gutter-b">
                    <!--begin::Body-->
                    <div class="card-body p-0">
                        <!--begin::Wizard-->
                        <div class="wizard wizard-1" id="kt_contact_add" data-wizard-state="step-first" data-wizard-clickable="true">
                            <!--begin::Wizard Nav-->
                            <div class="wizard-nav border-bottom">
                                <div class="wizard-steps p-8 p-lg-10">
                                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">1. PERSONAL INFORMATION</h3>
                                        </div>
                                    </div>
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">2. REPRESENTING PARTY</h3>
                                        </div>
                                    </div>
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">3. SECONDARY REPRESENTATIVE</h3>
                                        </div>
                                    </div>
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">4. PHYSICIAN OR MEDICAL GROUP</h3>
                                        </div>
                                    </div>
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">5. PHARMACY</h3>
                                        </div>
                                    </div>
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">6. DENTIST</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Wizard Nav-->
                            <!--begin::Wizard Body-->
                            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                                <div class="col-xl-12 col-xxl-7">
                                    <!--begin::Form Wizard Form-->
                                    <form class="form" id="kt_form" action="{{ route('resident.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <!--begin::Form Wizard Step 1-->
                                        <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                            <h5 class="text-dark font-weight-bold mb-10">PERSONAL INFORMATION</h5>

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('profile_logo') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label text-left">Avatar<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="image-input image-input-outline" id="kt_user_add_avatar">
                                                        <div class="image-input-wrapper"></div>
                                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Add avatar">
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name<span style="color: red;">*</span></label>
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
                                            <div class="form-group row {{ $errors->has('middlename') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Middle Name<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="middlename" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('middlename'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('middlename') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('lastname') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name<span style="color: red;">*</span></label>
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
                                            <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address<span style="color: red;">*</span></label>
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
                                            <div class="form-group row {{ $errors->has('birthday') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Date of birthday<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="birthday" type="date" data-format="mm/dd/yyyy" />
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">Gender<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <select class="form-control form-control-solid form-control-lg" name="gender">
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">Phone Number<span style="color: red;">*</span></label>
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
                                            <div class="form-group row {{ $errors->has('street1') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="street1" placeholder="Street" />
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

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('street2') ? 'has-error' : '' }}">
                                                <div class="col-lg-3 col-xl-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('street2'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street2') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('city') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('zip_code') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('zip_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('zip_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row {{ $errors->has('state') ? 'has-error' : '' }}">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State<span style="color: red;">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>

                                                @if ($errors->has('state'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Date Admitted</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="date_admitted" type="date" data-format="mm/dd/yyyy" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">SSN</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="ssn" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">PRIMARY LANGUAGE</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="primary_language" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 1-->
                                        <!--begin::Form Wizard Step 2-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h5 class="text-dark font-weight-bold mb-10">REPRESENTING PARTY</h5>

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="representing_party_firstname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="representing_party_lastname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="representing_party_street1" placeholder="Street" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <div class="col-xl-3 col-lg-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="representing_party_street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="representing_party_city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="representing_party_zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="representing_party_state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">HOME PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="representing_party_home_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">CELL PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="representing_party_cell_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 2-->
                                        <!--begin::Form Wizard Step 3-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h5 class="text-dark font-weight-bold mb-10">SECONDARY REPRESENTATIVE</h5>

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="secondary_representative_firstname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="secondary_representative_lastname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="secondary_representative_street1" placeholder="Street" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <div class="col-xl-3 col-lg-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="secondary_representative_street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="secondary_representative_city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="secondary_representative_zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="secondary_representative_state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">HOME PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="secondary_representative_home_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">CELL PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="secondary_representative_cell_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 3-->
                                        <!--begin::Form Wizard Step 4-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h5 class="text-dark font-weight-bold mb-10">PHYSICIAN OR MEDICAL GROUP</h5>

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_firstname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_lastname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_street1" placeholder="Street" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <div class="col-xl-3 col-lg-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">FAX </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="physician_or_medical_group_fax" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 4-->
                                        <!--begin::Form Wizard Step 5-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h5 class="text-dark font-weight-bold mb-10">PHARMACY</h5>

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">First Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="pharmacy_firstname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="pharmacy_lastname" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pharmacy_street1" placeholder="Street" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <div class="col-xl-3 col-lg-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pharmacy_street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pharmacy_city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pharmacy_zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pharmacy_state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">HOME PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="pharmacy_home_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">FAX </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="pharmacy_fax" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 5-->
                                        <!--begin::Form Wizard Step 6-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h5 class="text-dark font-weight-bold mb-10">DENTIST</h5>
                                            
                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">NAME </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="dentist_name" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Street</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="dentist_street1" placeholder="Street" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <div class="col-xl-3 col-lg-3"></div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="dentist_street2" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">City</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="dentist_city" placeholder="City" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Zip Code</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="dentist_zip_code" placeholder="Zip Code" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">State</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group input-group-solid input-group-lg">
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="dentist_state" placeholder="State" />
                                                    </div>
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">HOME PHONE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="dentist_home_phone" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">FAX </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="dentist_fax" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">ADVANCE DIRECTIVE </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="advance_directive" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">POLST </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="polst" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->

                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">ALERGIES </label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input class="form-control form-control-solid form-control-lg" name="alergies" type="text" />
                                                </div>

                                                <div class="fv-plugins-message-container"></div>
                                            </div>
                                            <!--end::Group-->
                                        </div>
                                        <!--end::Form Wizard Step 6-->
                                        <!--begin::Wizard Actions-->
                                        <div class="d-flex justify-content-between border-top pt-10">
                                            <div class="mr-2">
                                                <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Previous</button>
                                            </div>
                                            <div>
                                                <!-- <button type="button" class="btn btn-success" data-wizard-type="action-submit" style="display: initial!important;">Submit</button> -->

                                                <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">Submit</button>
                                                <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">Next Step</button>
                                            </div>
                                        </div>
                                        <!--end::Wizard Actions-->
                                    </form>
                                    <!--end::Form Wizard Form-->
                                </div>
                            </div>
                            <!--end::Wizard Body-->
                        </div>
                        <!--end::Wizard-->
                    </div>
                    <!--end::Body-->
                </div>                                            
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->

    <style type="text/css">
        .help-block {
            color: red;
        }
    </style>
@stop

@section('script')
    <script src="{{ asset('finaldesign/assets/js/pages/custom/user/add-user.js') }}"></script>
@endsection