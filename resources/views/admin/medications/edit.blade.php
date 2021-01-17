@extends('layouts.appsecond', ['menu' => 'medications'])

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
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Edit Medication</h5>
                    <!--end::Title-->
                    
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('medications.index') }}" class="text-muted">Medications &nbsp;</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->

                    <!--begin::Separator-->
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Search Form-->
                    <div class="d-flex align-items-center" id="kt_subheader_search">
                        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Enter medication details and submit</span>
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
                                            <form class="form" id="kt_form" action="{{ route('medications.update', $result->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" name="_method" value="put">

                                                <div class="row justify-content-center">
                                                    <div class="col-xl-9">
                                                        <!--begin::Wizard Step 1-->
                                                        <div class="my-5 step" data-wizard-type="step-content" data-type-status="current">
                                                            <!--begin::Group-->
                                                            <div class="form-group row {{ $errors->has('photo') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label text-left">Photo</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <?php 
                                                                        if(@$result->photo) {
                                                                            $path = asset('uploads/') . "/" . $result->photo;
                                                                        }else{
                                                                            $path = "";
                                                                        }
                                                                    ?>
                                                                    <div class="image-input image-input-outline" id="kt_user_edit_avatar" style="background-image: url(<?= $path ?>);">
                                                                        <div class="image-input-wrapper"></div>
                                                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                                            <input type="file" name="photo" accept=".png, .jpg, .jpeg" />
                                                                            <input type="hidden" name="profile_avatar_remove" />
                                                                        </label>
                                                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('photo'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('photo') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <!--end::Group-->

                                                            <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="name" type="text" required value="{{ $result->name }}" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('name'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group row {{ $errors->has('dose') ? 'has-error' : '' }}">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Dose</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input class="form-control form-control-solid form-control-lg" name="dose" type="text" required value="{{ $result->dose }}" />
                                                                </div>

                                                                <div class="fv-plugins-message-container"></div>

                                                                @if ($errors->has('dose'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('dose') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-xl-3 col-lg-3 col-form-label">Comments</label>
                                                                <div class="col-lg-9 col-xl-9">
                                                                    <input type="text" name="se" class="form-control form-control-solid form-control-lg" value="{{ $result->comments }}" id="kt_tagify_1">
                                                                    <div class="mt-3">
                                                                        <a href="javascript:;" id="kt_tagify_1_remove" class="btn btn-sm btn-light-primary font-weight-bold">Remove comments</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="comments" class="comments_real" value="">
                                                        </div>
                                                        <!--end::Wizard Step 1-->
                                                        
                                                        <!--begin::Wizard Actions-->
                                                        <div class="d-flex justify-content-between border-top pt-10 mt-15">
                                                            <div>
                                                                <button type="button" class="btn btn-success submit_btn" data-wizard-type="action-submit" style="display: initial!important;">Submit</button>

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
    <script src="{{ asset('finaldesign/assets/js/pages/custom/user/edit-medication.js') }}"></script>
    <script src="{{ asset('finaldesign/assets/js/pages/crud/forms/widgets/tagify.js') }}"></script>

    <script type="text/javascript">
        $('document').ready(function() {
            $('.submit_btn').click(function() {
                var comments = [];

                if ($('.tagify').children().length) {
                    $( "tag" ).each(function( index, element ) {
                        // element == this
                        comments.push($( element ).attr('title'));
                    });
                }

                $('.comments_real').val(comments);
            });
        });
    </script>
@endsection