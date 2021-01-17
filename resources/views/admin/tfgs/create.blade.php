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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Add PRN</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('tfgs.indextfg', $result['user']->id) }}" class="text-muted">PRN &nbsp;</a>
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
                                        <a href="{{ route('resident.show', $result['user']->id) }}" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $result['user']->firstname }}</a>
                                        <a href="{{ route('resident.show', $result['user']->id) }}">
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
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Add PRN</h3>
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <form action="{{ route('tfgs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="resident" value="{{ $result['user']->id }}">

                            <div class="row" >
                                <div class="col-lg-3 circle first_circle">
                                    <div class="form-group {{ $errors->has('medications') ? 'has-error' : '' }} circle_form">
                                        <label class="col-form-label">Medication</label>
                                        <select class="form-control medications" name="medications" required>
                                            <option value="">Choose Medication</option>
                                            @foreach($result['medications'] as $ac)
                                                <option value="{{ $ac->id }}">{{ $ac->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('medications'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('medications') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    
                                <div class="col-lg-3 circle">
                                    <div class="form-group {{ $errors->has('time') ? 'has-error' : '' }} circle_form">
                                        <label class="col-form-label">Time</label>
                                        <input type="time" class="form-control" name='time' placeholder="Time" value="<?= date('H:i'); ?>" required id="time">
                                        @if ($errors->has('time'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 circle">
                                    <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }} circle_form">
                                        <label class="col-form-label">Comment </label>
                                        <input type="text" class="form-control" id="comment" name="comment" placeholder="Comment" value="{{ old('comment') }}">
                                        @if ($errors->has('comment'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 circle">
                                    <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }} circle_form">
                                        <label class="col-form-label">Attached File </label>
                                        <input type="file" name="file" class="form-control" id="file" placeholder="Attached File">
                                        @if ($errors->has('file'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('file') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="padding-bottom-30">
                                <div class="">
                                    <button style="display: none;" type="submit" class="btn btn-primary gradient-blue submit_btn">Submit</button>
                                </div>
                            </div>
                        </form>

                        <div class="padding-bottom-30">
                            <div class="">
                                <button class="btn btn-primary gradient-blue validate_btn">Submit</button>
                            </div>
                        </div>
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
<script>
    $(document).ready(function(){
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});
        // $('.circle').css({'line-height':cw+parseInt(30)+'px'});

        $('.validate_btn').click(function() {
            var activity = $('.medications').val();
            if (activity == '') {
                $('.first_circle').css('background-color', '#ea6b6b');
            }

            $('.submit_btn').click();
        });

        $('.medications').change(function() {
            var activity = $(this).val();
            if (activity != '') {
                $('.first_circle').css('background-color', '#1cc6d8');
            }
        });
    });

    $(window).resize(function(){
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});
        // $('.circle').css({'line-height':cw+parseInt(30)+'px'});
    });
</script>
@endsection