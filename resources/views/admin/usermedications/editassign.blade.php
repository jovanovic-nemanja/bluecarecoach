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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Assign Medication</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('usermedications.indexusermedication', $result['user']->id) }}" class="text-muted">Medications &nbsp;</a>
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
                            <h3 class="card-label">Assign Medication</h3>
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <form action="{{ route('usermedications.update', $result['usermedications']->id) }}" method="POST">
                            @csrf

                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="assign" value="1">
                            <input type="hidden" name="resident" value="{{ $result['user']->id }}">

                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('medications') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Medications</label>
                                        <select class="form-control medications" name="medications" required>
                                            <option value="">Choose Medication</option>
                                            @foreach($result['allmedications'] as $ac)
                                                <option <?php if($ac->id==$result["medication"]->id){echo 'selected';} ?> value="{{ $ac->id }}">{{ $ac->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('medications'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('medications') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('dose') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Dose</label>
                                        <input type="number" class="form-control" name='dose' placeholder="Dose" value="{{ $result['usermedications']->dose }}" required id="dose">
                                        
                                        @if ($errors->has('dose'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('dose') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('route') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Route </label>
                                        <select class="form-control" id="route" name="route">
                                            <?php 
                                                foreach ($result['routes'] as $com) { ?>
                                                    <option <?php if($result['usermedications']->route == $com['id']){echo 'selected';} ?> value="<?= $com['id'] ?>"><?= $com['name'] ?></option>
                                            <?php } ?>
                                        </select>

                                        @if ($errors->has('route'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('route') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('time') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Time </label>
                                        <input type="time" class="form-control" id="time" name="time" placeholder="Time" value="{{ $result['usermedications']->time }}">
                                        @if ($errors->has('time'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('time') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="form-group {{ $errors->has('start_day') ? 'has-error' : '' }}">
                                        <label class="col-form-label">Start day</label>
                                        <input type="date" name="start_day" id="start_day" class="form-control start_day" required value="{{ $result['usermedications']->start_day }}">

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
                                        <input type="date" name="end_day" id="end_day" class="form-control end_day" required value="{{ $result['usermedications']->end_day }}">
                                        
                                        @if ($errors->has('end_day'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('end_day') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="padding-bottom-30" style="text-align: center; padding-top: 5%;">
                                <div class="">
                                    <button type="submit" class="btn btn-primary gradient-blue">Submit</button>
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
    
</script>
@endsection