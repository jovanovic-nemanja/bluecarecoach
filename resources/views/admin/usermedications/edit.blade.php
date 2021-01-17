@extends('layouts.appsecond', ['menu' => 'residents'])

@section('content')

	@if(session('flash'))
		<div class="alert alert-primary">
			{{ session('flash') }}
		</div>
	@endif
	<div class="col-xs-12">
        <div class="page-title">

            <div class="pull-left">
                <!-- PAGE HEADING TAG - START -->
                <h1 class="title">Edit Resident Medication </h1>
                <!-- PAGE HEADING TAG - END -->
            </div>

        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-xs-12">
        <div class="add-header-wrapper gradient-blue curved-section text-center">
            <div class="doctors-head relative text-center">
                <div class="patient-img img-circle">
                    <a href="{{ route('resident.show', $result['user']->id) }}">
                        <img src="{{ asset('uploads/').'/'.$result['user']->profile_logo }}" class="rad-50 center-block">

                        <h4 style="color: #fff;">{{ $result['user']->firstname }}</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class=" bg-w">
            <div class="col-lg-10 col-lg-offset-1 col-lg-12">
                <section class="box ">
                    <header class="panel_header">
                        <div class="row" style="text-align: center;">
                            
                        </div>
                        <div class="actions panel_actions pull-right">
                            <a class="box_toggle fa fa-chevron-down"></a>
                        </div>
                    </header>
                    <div class="content-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ route('usermedications.update', $result['usermedications']->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="put">

                                    <div class="row">
                                        <input type="hidden" name="resident" value="{{ $result['user']->id }}">

                                        <div class="col-lg-3 circle first_circle">
                                            <div class="form-group {{ $errors->has('medications') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Medication</label>
                                                <select class="form-control medications" name="medications" required>
                                                    <option value="">Choose Medication</option>
                                                    @foreach($result['allmedications'] as $ac)
                                                        <option <?php if($ac->id==$result["medication"]->id){echo 'selected';} ?> value="{{ $ac->id }}">{{ $ac->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            @if ($errors->has('medications'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('medications') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('daily_count') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Daily Count</label>
                                                <input type="number" class="form-control" name='daily_count' placeholder="Daily Count" value="{{ $result['usermedications']->daily_count }}" required id="daily_count">
                                                
                                                @if ($errors->has('daily_count'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('daily_count') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Duration</label>
                                                <input type="number" class="form-control" name='duration' placeholder="Duration" value="{{ $result['usermedications']->duration }}" required id="duration">

                                                @if ($errors->has('duration'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('duration') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Comment</label>
                                                <input type="text" class="form-control" id="comment" name="comment" placeholder="Comment" value="{{ $result['usermedications']->comment }}">
                                                @if ($errors->has('comment'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('comment') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('file') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Attached File</label>
                                                <input type="file" name="file" class="form-control" id="file" placeholder="Attached File" value="{{ asset('uploads/').'/'.$result['usermedications']->file }}">
                                                @if ($errors->has('file'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('file') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="padding-bottom-30" style="text-align: center; padding-top: 5%;">
                                        <div class="">
                                            <button style="display: none;" type="submit" class="btn btn-primary gradient-blue submit_btn">Submit</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="padding-bottom-30" style="text-align: center; padding-top: 5%;">
                                    <div class="">
                                        <button class="btn btn-primary gradient-blue validate_btn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
    $(document).ready(function(){
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

    $(document).ready(function(){
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});
    });

    $(window).resize(function(){
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});
    });
</script>
@endsection