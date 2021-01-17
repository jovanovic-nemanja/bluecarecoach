@extends('layouts.appsecond', ['menu' => ''])

@section('content')

	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-3 py-lg-8 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">General Settings</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-muted">home &nbsp;</a>
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
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-body">
                        <form action="{{ route('admin.generalsetting.update', $general_setting->id) }}" method="POST">
                            @csrf

                            <input type="hidden" name="_method" value="put">

                            <div class="form-group">
                                <label class="col-form-label">Site Name</label>
                                <div class="controls">
                                    <input type="text" name="site_name" class="form-control" placeholder="Site Name" value="{{ $general_setting->site_name }}" />
                                </div>
                            </div>

                            <div class="form-group">
								<label class="col-form-label">Site Title</label>
								<div class="controls">	
									<input type="text" name="site_title" class="form-control" placeholder="Site Title" value="{{ $general_setting->site_title }}" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-form-label">Site Subtitle</label>
								<div class="controls">
									<input type="text" name="site_subtitle" class="form-control" placeholder="Site Subtitle" value="{{ $general_setting->site_subtitle }}" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-form-label">Site Description</label>
								<div class="controls">
									<textarea name="site_desc" placeholder="Site Description" class="form-control" cols="30" rows="5">{{ $general_setting->site_desc }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-form-label">Site Footer</label>
								<div class="controls">
									<input type="text" name="site_footer" class="form-control" placeholder="Site Footer" value="{{ $general_setting->site_footer }}" />
								</div>
							</div>

                            <div class="padding-bottom-30" style="text-align: center;">
                                <div class="">
                                    <button type="submit" class="btn btn-primary gradient-blue submit_btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection