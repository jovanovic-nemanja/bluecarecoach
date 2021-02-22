@extends('layouts.appsecond', ['menu' => 'credentials'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Credential</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('credentials.index') }}" class="text-muted">Credentials &nbsp;</a>
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
                        <form action="{{ route('credentials.update', $result->id) }}" method="POST">
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

                            <div class="form-group {{ $errors->has('care_licenses') ? 'has-error' : '' }}">
                                <label class="col-form-label">Required(NAR, NAC, HCA)</label>
                                <div class="controls">
                                    <select name="care_licenses[]" required class="form-control select2" multiple="multiple" id="kt_select2_3">
                                        @foreach($care_licenses as $licen)
                                            <?php 
                                                $arr1 = $result->care_licenses;
                                                $arr2 = str_replace('[', '', $arr1);
                                                $arr3 = str_replace(']', '', $arr2);
                                                $diff = explode(",", $arr3);
                                                if (@$diff) {
                                                    $arr = [];
                                                    for ($i=0; $i < count($diff); $i++) { 
                                                        array_push($arr, $diff[$i]);
                                                    }
                                                } ?>
                                                    @if(in_array($licen->id, $arr))
                                                        <option value="{{ $licen->id }}" selected>{{ $licen->name }}</option>
                                                    @else
                                                        <option value="{{ $licen->id }}">{{ $licen->name }}</option>
                                                    @endif
                                            <?php  ?>
                                        @endforeach
                                    </select>
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
@stop

@section('script')
    <script src="{{ asset('finaldesign/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
@endsection