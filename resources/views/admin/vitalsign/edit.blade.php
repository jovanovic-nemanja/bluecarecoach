@extends('layouts.appsecond', ['menu' => 'residents'])

@section('content')

	@if(session('flash'))
		<div class="alert alert-primary">
			{{ session('flash') }}
		</div>
	@endif

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-3 py-lg-8 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Vital Sign</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('vitalsign.indexresidentvitalsign', $result['data']->resident_id) }}" class="text-muted">Vital Sign &nbsp;</a>
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
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <!--begin::Details-->
                        <div class="d-flex mb-9">
                            <!--begin: Pic-->
                            <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                                <div class="symbol symbol-50 symbol-lg-120">
                                    <img src="{{ asset('uploads/').'/'.$result['user']->profile_logo }}" alt="image" class="custom_img_tag">
                                </div>
                                <div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
                                    <span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between flex-wrap mt-1">
                                    <div class="d-flex mr-3">
                                        <a href="{{ route('resident.show', $result['data']->resident_id) }}" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $result['user']->firstname }}</a>
                                        <a href="{{ route('resident.show', $result['data']->resident_id) }}">
                                            <i class="flaticon2-correct text-success font-size-h5"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>

                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <!--begin::Body-->
                    <div class="card-body p-0">
                        <!--begin::Wizard-->
                        <div class="wizard wizard-1" id="kt_contact_add" data-wizard-state="step-first" data-wizard-clickable="true">
                            <!--begin::Wizard Body-->
                            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                                <div class="col-xl-12 col-xxl-7">
                                    <!--begin::Form Wizard Form-->
                                    <form class="form" id="kt_contact_add_form" action="{{ route('vitalsign.update', $result['data']->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="put">
                                        <input type="hidden" name="resident_id" value="{{ $result['data']->resident_id }}">

                                        <div class="row" >
                                            <div class="col-xl-12">
                                                @if($result['data']->type == 1)
                                                    <div class="form-group row {{ $errors->has('data') ? 'has-error' : '' }}">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Temperature <span>(°F)</span></label>
                                                        <i class='fas fa-thermometer' style='font-size: 70px; color: red;'></i>
                                                        
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input class="form-control form-control-lg form-control-solid" name="data" type="text" value="{{ $result['data']->data }}" required id="temperature" />
                                                        </div>

                                                        @if ($errors->has('data'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('data') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @elseif($result['data']->type == 2)
                                                    <div class="form-group row {{ $errors->has('data') ? 'has-error' : '' }}">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Blood Pressure <span>(mmHG)</span></label>
                                                        <i class="fa fa-signal" aria-hidden="true" style="font-size: 70px; color: red;"></i>

                                                        <div class="col-lg-9 col-xl-9">
                                                            <input class="form-control form-control-lg form-control-solid" name="data" type="text" value="{{ $result['data']->data }}" required id="blood_pressure" />
                                                        </div>

                                                        @if ($errors->has('data'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('data') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @elseif($result['data']->type == 3)
                                                    <div class="form-group row {{ $errors->has('data') ? 'has-error' : '' }}">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Heart Rate <span>(Per min)</span></label>
                                                        <i class="fa fa-heart" aria-hidden="true" style="font-size: 70px; color: red;"></i>

                                                        <div class="col-lg-9 col-xl-9">
                                                            <input class="form-control form-control-lg form-control-solid" name="data" type="text" value="{{ $result['data']->data }}" required id="heart_rate" />
                                                        </div>
                                                        
                                                        @if ($errors->has('data'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('data') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!--begin::Wizard Actions-->
                                        <div class="d-flex justify-content-between border-top pt-10">
                                            <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4">Submit</button>
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
@stop

@section('script')
    <script src="{{ asset('finaldesign/assets/js/pages/custom/contacts/add-contact.js') }}"></script>
@endsection