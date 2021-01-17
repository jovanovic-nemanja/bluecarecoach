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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Activity</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('useractivities.indexuseractivity', $result['user']->id) }}" class="text-muted">Activities &nbsp;</a>
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
                            @if($result['type'] == 1)
                                <h3 class="card-label">Primary ADL</h3>
                            @else
                                <h3 class="card-label">Secondary ADL</h3>
                            @endif
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <form action="{{ route('useractivities.update', $result['useractivities']->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="put">

                            <div class="row">
                                <input type="hidden" name="resident" value="{{ $result['user']->id }}">

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('activities') ? 'has-error' : '' }}">
                                        <label class="form-label">Activity</label>
                                        <select class="form-control activities" name="activities" required>
                                            <option value="">Choose Activity</option>
                                            @foreach($result['activities'] as $ac)
                                                <option <?php if($ac->id==$result["activity"]->id){echo 'selected';} ?> value="{{ $ac->id }}">{{ $ac->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    @if ($errors->has('activities'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('activities') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
                                        <label class="form-label">Comment</label>
                                        <select class="form-control" id="comment" name="comment">
                                            <?php 
                                                if ($result['useractivities']->comment == -1) { ?>
                                                    <option value>Choose Comment</option>
                                                    <option value='-1' selected>Other</option>
                                                    <?php foreach ($result['comments'] as $com) { ?>
                                                        <option value="<?= $com['id'] ?>"><?= $com['name'] ?></option>
                                            <?php } ?>
                                            <?php }else{ ?>
                                                    <option value>Choose Comment</option>
                                                    <option value='-1'>Other</option>
                                                    <?php foreach ($result['comments'] as $com) { ?>
                                                        <option <?php if($result['useractivities']->comment == $com['id']){echo 'selected';} ?> value="<?= $com['id'] ?>"><?= $com['name'] ?></option>
                                            <?php } } ?>
                                        </select>

                                        <?php 
                                            if ($result['useractivities']->comment == -1) { ?>
                                                <input type="text" name="other_comment" id="other_comment" class="form-control" value="{{ $result['useractivities']->other_comment }}" />
                                        <?php } ?>
                                        
                                        @if ($errors->has('comment'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('start_day') ? 'has-error' : '' }}">
                                        <label class="form-label">Start day</label>
                                        <input type="date" name="start_day" id="start_day" class="form-control start_day" required value="{{ $result['useractivities']->start_day }}" />

                                        @if ($errors->has('start_day'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('start_day') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('end_day') ? 'has-error' : '' }}">
                                        <label class="form-label">End day</label>
                                        <input type="date" name="end_day" id="end_day" class="form-control end_day" required value="{{ $result['useractivities']->end_day }}" />
                                        
                                        @if ($errors->has('end_day'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('end_day') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label class="form-label">Duration</label>
                                        <select class="form-control" id="duration" name="type" required>
                                            <option value="">Choose</option>
                                            <option value="1" <?php if($result["useractivities"]->type == 1){echo 'selected';} ?>>Daily</option>
                                            <option value="2" <?php if($result["useractivities"]->type == 2){echo 'selected';} ?>>Weekly</option>
                                            <option value="3" <?php if($result["useractivities"]->type == 3){echo 'selected';} ?>>Monthly</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-9">
                                    <div id="Daily_area">
                                        <div class="col-lg-3">
                                            <div class="form-group {{ $errors->has('time') ? 'has-error' : '' }}">
                                                <label class="form-label">Time</label>
                                                <input type="time" class="form-control" name='daily_time' placeholder="Time" id="daily_time" value="{{ $result['useractivities']->time }}">
                                                @if ($errors->has('time'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Weekly_area">
                                        <div class="col-lg-3">
                                            <div><label>Week</label></div>
                                            <select class="form-control" id="weeks" name="weeks">
                                                <option value="1" <?php if($result["useractivities"]->day == 1){echo 'selected';} ?>>Monday</option>
                                                <option value="2" <?php if($result["useractivities"]->day == 2){echo 'selected';} ?>>Tuesday</option>
                                                <option value="3" <?php if($result["useractivities"]->day == 3){echo 'selected';} ?>>Wednesday</option>
                                                <option value="4" <?php if($result["useractivities"]->day == 4){echo 'selected';} ?>>Thursday</option>
                                                <option value="5" <?php if($result["useractivities"]->day == 5){echo 'selected';} ?>>Friday</option>
                                                <option value="6" <?php if($result["useractivities"]->day == 6){echo 'selected';} ?>>Saturday</option>
                                                <option value="7" <?php if($result["useractivities"]->day == 7){echo 'selected';} ?>>Sunday</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Time</label>
                                                <input type="time" class="form-control" name='weekly_time' placeholder="Time" id="weekly_time" value="{{ $result['useractivities']->time }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Monthly_area">
                                        <div class="col-lg-3">
                                            <div><label>Month(days)</label></div>
                                            <select class="form-control" id="months" name="months">
                                                @for($i = 1; $i < 31; $i++)
                                                    <option value="{{ $i }}" <?php if($result["useractivities"]->day == $i){echo 'selected';} ?>>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Time</label>
                                                <input type="time" class="form-control" name='monthly_time' placeholder="Time" id="monthly_time" value="{{ $result['useractivities']->time }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="padding-bottom-30" style="text-align: center; padding-top: 5%;">
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
<script>
    $(document).ready(function(){
        $('.activities').change(function() {
            $('#comment').empty();
            var activity = $(this).val();
            if (activity != '') {
                var url = $('#url').val();
                $.ajax({
                    url: '/getcommentsbyactivity',
                    type: 'GET',
                    data: { activity : activity },
                    success: function(result, status) {
                        if (status) {
                            $('#comment').empty();
                            var element = "<option value>Choose Comment</option><option value='-1'>Other</option>";
                            for (var i = 0; i < result.length; i++) {
                                element += "<option value=" + result[i]['id'] + ">" + result[i]['name'] + "</option>";
                            }
                            $('#comment').append(element);
                        }
                    }
                })
            }
        });

        $('#comment').change(function() {
            var cur_val = $(this).val();
            if (cur_val == -1) {
                $('#comment').parent().append("<input type='text' class='form-control' name='other_comment' id='other_comment' />");
            }else{
                $('#other_comment').remove();
            }
        });

        var type = "<?= $result["useractivities"]->type ?>";
        if (type == 1) {    //daily
            $('#Daily_area').show();
            $('#Weekly_area').hide();
            $('#Monthly_area').hide();
        }if (type == 2) {    //weekly
            $('#Daily_area').hide();
            $('#Weekly_area').show();
            $('#Monthly_area').hide();
        }if (type == 3) {    //monthly
            $('#Daily_area').hide();
            $('#Weekly_area').hide();
            $('#Monthly_area').show();
        }            
    });
</script>
@endsection