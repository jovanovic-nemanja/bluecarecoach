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
                <h1 class="title">Add Resident Medication </h1>
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
                    <h4 style="color: #fff;">{{ $result['medication']->firstname }}</h4>
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
                                <form action="{{ route('usermedications.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="resident" value="{{ $result['user']->id }}">
                                    <input type="hidden" name="assign_id" value="{{ $result['assign_id'] }}">

                                    <div class="row" >
                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('dose') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Dose</label>
                                                <input type="number" class="form-control" name='dose' placeholder="Dose" value="{{ $result['assigns']['dose'] }}" required id="dose" disabled>
                                                
                                                @if ($errors->has('dose'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('dose') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-3 circle">
                                            <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }} circle_form">
                                                <label class="form-label">Duration</label>
                                                <input type="number" class="form-control" name='duration' placeholder="Duration" value="{{ $result['assigns']['duration'] }}" required id="duration" disabled>

                                                @if ($errors->has('duration'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('duration') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 circle">
                                            <?php 
                                                $cur_date = App\Assignmedications::getRemainingDays($result['assigns']['created_at'])
                                            ?>
                                            <div class="form-group circle_form">
                                                <label class="form-label">Remaining day </label>
                                                <input type="text" class="form-control" placeholder="Remaining day" disabled value="<?= $cur_date; ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 circle">
                                            <div class="form-group circle_form">
                                                <label class="form-label">Current Time </label>
                                                <input type="text" class="form-control" id="current_time" name="current_time" placeholder="Current Time" disabled>
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
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});

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

        function livecurrenttime (){
            setInterval(function()
            { 
                $.ajax({
                    url: '/getCurrentTimeByAjax',
                    data: {},
                    type: 'GET',
                    success: function(result, status) {
                      if (status) {
                        $('#current_time').val(result);
                      }
                    }
                });
            }, 1000);
        }; 

        livecurrenttime ();
    });

    $(window).resize(function(){
        var cw = $('.circle').width();
        $('.circle').css({'height':cw+parseInt(30)+'px'});
    });
</script>
@endsection