@extends('layouts.appsecond', ['menu' => 'manageresident'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Residents</h2>
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
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Manage Residents
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <a href="{{ route('resident.add') }}" class="btn btn-primary font-weight-bolder">Add Resident</a>
                            <!--end::Button-->
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Birthday</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(@$residents) {
                                        $i = 1;
                                        foreach($residents as $resident) { ?>
                                            @if($resident->hasRole('resident'))
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $resident->firstname }}</td>
                                                    <td>{{ App\User::getGender($resident->gender) }}</td>
                                                    <td>
                                                        <div class="">
                                                            <h6><?= $resident->email; ?></h6>
                                                        </div>
                                                    </td>
                                                    <td>{{ $resident->birthday }}</td>
                                                    <td>{{ $resident->street1 }}</td>
                                                    <td>
                                                        <span class="badge round-primary">{{ $resident->phone_number }}</span>
                                                    </td>
                                                    <td>
                                                        @if($resident->profile_logo)
                                                            <div class="symbol symbol-circle symbol-lg-75">
                                                                <img src="{{ asset('uploads/').'/'.$resident->profile_logo }}" class="rad-50 center-block custom_img_tag" alt="">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('resident.edit', $resident->id) }}" class="btn btn-success">Edit</a>

                                                        <a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('delete-form-{{$resident->id}}').submit();">Delete</a>

                                                        <form id="delete-form-{{$resident->id}}" action="{{ route('resident.destroy', $resident->id) }}" method="POST" style="display: none;">
                                                            <input type="hidden" name="_method" value="delete">
                                                            @csrf
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                <?php $i++; } }else{ ?>

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