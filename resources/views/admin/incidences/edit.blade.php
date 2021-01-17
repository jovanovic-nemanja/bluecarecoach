@extends('layouts.appsecond', ['menu' => 'incidences'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Incidence</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('incidences.index') }}" class="text-muted">Incidences &nbsp;</a>
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
                        <form action="{{ route('incidences.update', $result->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="put">

                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label class="col-form-label">Title</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='title' placeholder="Title" required value="{{ $result->title }}">
                                </div>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                                <label class="col-form-label">Content</label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="content" name="content" placeholder="Content" value="{{ $result->content }}">
                                </div>
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <label class="col-form-label">Type</label>
                                <div class="controls">
                                    <?php if($result->type == 1) {
                                        $selected1 = "selected"; 
                                        $selected3 = $selected2 = "";
                                    }else{
                                        $selected3 = "selected"; 
                                        $selected1 = $selected2 = "";
                                    } ?>
                                    <select class="form-control" name="type" required>
                                        <option value="">Choose Type</option>
                                        <option value="1" <?= $selected1; ?>>Family Visit</option>
                                        <option value="2" <?= $selected2; ?>>Mood Change</option>
                                        <option value="3" <?= $selected3; ?>>Body Harm</option>
                                    </select>
                                </div>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
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
@stop