@extends('layouts.appsecond', ['menu' => 'activities'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Activity</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('activities.index') }}" class="text-muted">Activities &nbsp;</a>
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
                        <form action="{{ route('activities.update', $result->id) }}" method="POST">
                            @csrf

                            <input type="hidden" name="_method" value="put">

                            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                <label class="col-form-label">Type</label>
                                <div class="controls">
                                    <?php if($result->type == 1) {
                                        $selected1 = "selected"; 
                                        $selected2 = "";
                                    }else{
                                        $selected2 = "selected"; 
                                        $selected1 = "";
                                    } ?>
                                    <select class="form-control" name="type" required>
                                        <option value="">Choose Type</option>
                                        <option value="1" <?= $selected1; ?>>Primary ADL</option>
                                        <option value="2" <?= $selected2; ?>>Secondary ADL</option>
                                    </select>
                                </div>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>

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

                            <div class="form-group">
                                <label class="col-form-label">Comments</label>
                                <div class="controls">
                                    <input type="text" name="se" class="form-control" placeholder="Comments" id="kt_tagify_1" value="{{ $result->comments }}">
                                    <div class="mt-3">
                                        <a href="javascript:;" id="kt_tagify_1_remove" class="btn btn-sm btn-light-primary font-weight-bold">Remove comments</a>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="comments" class="comments_real" value="">

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