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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Assign Activity</h2>
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
                        <form action="{{ route('useractivities.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="resident" value="{{ $result['user']->id }}">

                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('activities') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Activity</label>
                                        <select class="form-control activities" name="activities" required>
                                            <option value="">Choose Activity</option>
                                            @foreach($result['activities'] as $ac)
                                                <option value="{{ $ac->id }}">{{ $ac->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('activities'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('activities') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Comment </label>
                                        <select class="form-control" id="comment" name="comment">
                                            
                                        </select>
                                        @if ($errors->has('comment'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 other_comment">
                                    <div class="form-group {{ $errors->has('other_comment') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Other Comment </label>
                                        <input type='text' class='form-control' name='other_comment' id='other_comment' />
                                        
                                        @if ($errors->has('other_comment'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('other_comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('start_day') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Start day</label>
                                        <input type="date" name="start_day" id="start_day" class="form-control start_day" required>

                                        @if ($errors->has('start_day'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('start_day') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('end_day') ? 'has-error' : '' }}">
                                        <label class="col-form-label">End day</label>
                                        <input type="date" name="end_day" id="end_day" class="form-control end_day" required>
                                        
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
                                        <label class="col-form-label">Type</label>
                                        <select class="form-control" id="duration" name="type" required>
                                            <option value="">Choose</option>
                                            <option value="1">Daily</option>
                                            <option value="2">Weekly</option>
                                            <option value="3">Monthly</option>
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
                                            <div class="form-group {{ $errors->has('time1') ? 'has-error' : '' }}">
                                                <label class="col-form-label">Time1</label>
                                                <input type="time" class="form-control" name='time1' placeholder="Time1" id="time1">
                                                @if ($errors->has('time1'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time1') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group {{ $errors->has('time2') ? 'has-error' : '' }}">
                                                <label class="col-form-label">Time2</label>
                                                <input type="time" class="form-control" name='time2' placeholder="Time2" id="time2">
                                                @if ($errors->has('time2'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time2') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group {{ $errors->has('time3') ? 'has-error' : '' }}">
                                                <label class="col-form-label">Time3</label>
                                                <input type="time" class="form-control" name='time3' placeholder="Time3" id="time3">
                                                @if ($errors->has('time3'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time3') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group {{ $errors->has('time4') ? 'has-error' : '' }}">
                                                <label class="col-form-label">Time4</label>
                                                <input type="time" class="form-control" name='time4' placeholder="Time4" id="time4">
                                                @if ($errors->has('time4'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time4') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Weekly_area">
                                        <div><label class="col-form-label">Week</label><br></div>
                                        <div class="col-lg-12" style="display: inline-flex;">
                                            
                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Monday</label>
                                                <input type="checkbox" class="Monday" name='weeks[]' id="Monday" value="1">
                                            </div>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Tuesday</label>
                                                <input type="checkbox" class="Tuesday" name='weeks[]' id="Tuesday" value="2">
                                            </div>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Wednesday</label>
                                                <input type="checkbox" class="Wednesday" name='weeks[]' id="Wednesday" value="3">
                                            </div>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Thursday</label>
                                                <input type="checkbox" class="Thursday" name='weeks[]' id="Thursday" value="4">
                                            </div>
                                            <br><br>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Friday</label>
                                                <input type="checkbox" class="Friday" name='weeks[]' id="Friday" value="5">
                                            </div>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Saturday</label>
                                                <input type="checkbox" class="Saturday" name='weeks[]' id="Saturday" value="6">
                                            </div>

                                            <div class="col-lg-3 custom_col-3">
                                                <label class="col-form-label">Sunday</label>
                                                <input type="checkbox" class="Sunday" name='weeks[]' id="Sunday" value="7">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <br>
                                            <div class="form-group">
                                                <label class="col-form-label">Time</label>
                                                <input type="time" class="form-control" name='weekly_time' placeholder="Time" id="time">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="Monthly_area">
                                        <div class="col-lg-12">
                                            <div><label>Month(days)</label></div>
                                            <div class="row">
                                                @for($i = 1; $i < 11; $i++)
                                                    <div class="col-lg-1">
                                                        <label class="col-form-label">{{ $i }}</label>
                                                        <input type="checkbox" class="date" name='months[]' id="date" value="{{ $i }}">
                                                    </div>
                                                @endfor
                                            </div>
                                            <br>
                                            
                                            <div class="row">
                                                @for($i = 11; $i < 21; $i++)
                                                    <div class="col-lg-1">
                                                        <label class="col-form-label">{{ $i }}</label>
                                                        <input type="checkbox" class="date" name='months[]' id="date" value="{{ $i }}">
                                                    </div>
                                                @endfor
                                            </div>
                                            <br>

                                            <div class="row">
                                                @for($i = 21; $i < 31; $i++)
                                                    <div class="col-lg-1">
                                                        <label class="col-form-label">{{ $i }}</label>
                                                        <input type="checkbox" class="date" name='months[]' id="date" value="{{ $i }}">
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <br>
                                            <div class="form-group">
                                                <label class="col-form-label">Time</label>
                                                <input type="time" class="form-control" name='monthly_time' placeholder="Time" id="time">
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
        $('#Daily_area').show();
        $('#Weekly_area').hide();
        $('#Monthly_area').hide();

        $('.other_comment').hide();

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
                $('.other_comment').show();
            }else{
                $('.other_comment').hide();
            }
        });
    });
</script>
@endsection