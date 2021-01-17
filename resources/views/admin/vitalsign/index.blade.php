@extends('layouts.appsecond', ['menu' => 'residents'])

@section('content')
	@if(session('flash'))
		<div class="alert alert-success">
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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Vital Sign</h2>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-muted">Home &nbsp;</a>
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
                                    <img src="{{ asset('uploads/').'/'.$user->profile_logo }}" alt="image" class="custom_img_tag">
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
                                        <a href="{{ route('resident.show', $user->id) }}" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $user->firstname }}</a>
                                        <a href="{{ route('resident.show', $user->id) }}">
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
                            <h3 class="card-label">Vital Sign</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('vitalsign.createvitalsign', $user->id) }}" class="btn btn-success">Add</a>
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(@$vitalsigns) {
                                        $i = 1;
                                        foreach($vitalsigns as $vitalsign) { ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $vitalsign->sign_date }}</td>
                                                <td>{{ App\Vitalsign::getType($vitalsign->type) }}</td>
                                                <td>
                                                    <div class="">
                                                        <h6><?= $vitalsign->data; ?></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('vitalsign.show', $vitalsign->id) }}" class="btn btn-success">Edit</a>
                                                    <a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('delete-form-{{$vitalsign->id}}').submit();">Delete</a>

                                                    <form id="delete-form-{{$vitalsign->id}}" action="{{ route('vitalsign.destroy', $vitalsign->id) }}" method="POST" style="display: none;">
                                                        <input type="hidden" name="_method" value="delete">
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                <?php $i++; } } else{ ?>

                                <?php } ?>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
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