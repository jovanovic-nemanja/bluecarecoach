@extends('layouts.appsecond', ['menu' => 'emailsettings'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Edit Email Setting</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('emailsettings.index') }}" class="text-muted">Email Settings &nbsp;</a>
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
                        <form action="{{ route('emailsettings.update', $emailsettings->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="col-form-label">Type</label>
                                <div class="controls">
                                    <select class="form-control type_emailsettings" name='type' required>
                                        <option value=''>Choose item...</option>
                                        <option value='1' <?php if($emailsettings['type'] == 1){echo 'selected';} ?>>Email Verification</option>
                                        <option value='2' <?php if($emailsettings['type'] == 2){echo 'selected';} ?>>Forgot Password</option>
                                        <option value='3' <?php if($emailsettings['type'] == 3){echo 'selected';} ?>>Looking For Job Status</option>
                                        <option value='4' <?php if($emailsettings['type'] == 4){echo 'selected';} ?>>Expiry Reminder Cronjob</option>
                                        <option value='5' <?php if($emailsettings['type'] == 5){echo 'selected';} ?>>Expiry Reminder Schedule less 31days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('from_address') ? 'has-error' : '' }}">
                                <label class="col-form-label">From Address</label>
                                <div class="controls">
                                    <input type="email" class="form-control" name='from_address' placeholder="From Address" value="{{ $emailsettings->from_address }}" required>
                                </div>
                                @if ($errors->has('from_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('from_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group {{ $errors->has('from_title') ? 'has-error' : '' }}">
                                <label class="col-form-label">From Title</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='from_title' placeholder="From Title" value="{{ $emailsettings->from_title }}" required>
                                </div>
                                @if ($errors->has('from_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('from_title') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                <label class="col-form-label">Subject</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='subject' placeholder="From Title" value="{{ $emailsettings->subject }}" required>
                                </div>
                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('content_name') ? 'has-error' : '' }} content_name">
                                <label class="col-form-label">Content Header</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='content_name' placeholder="Content Header" value="{{ $emailsettings->content_name }}">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('content_body') ? 'has-error' : '' }} content_body">
                                <label class="col-form-label">Content Body</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='content_body' placeholder="Content Body" value="{{ $emailsettings->content_body }}">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('pre_footer') ? 'has-error' : '' }} pre_footer">
                                <label class="col-form-label">Pre footer</label>
                                <div class="controls">
                                    <textarea class="form-control" name='pre_footer' placeholder="Pre footer" rows="8">{{ nl2br($emailsettings->pre_footer) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('footer') ? 'has-error' : '' }} footer">
                                <label class="col-form-label">Footer</label>
                                <div class="controls">
                                    <input type="text" class="form-control" name='footer' placeholder="Footer" value="{{ $emailsettings->footer }}">
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
    <script src="{{ asset('js/custom.js') }}"></script>
@endsection