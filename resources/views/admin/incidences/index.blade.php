@extends('layouts.appsecond', ['menu' => 'incidences'])

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Incidences</h2>
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
                            <h3 class="card-label">Incidences</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('incidences.create') }}" class="btn btn-primary font-weight-bolder">Add</a>
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($incidences) {
                                        $i = 1;
                                        foreach($incidences as $incidence) { ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>
                                                    {{ $incidence->title }}
                                                </td>
                                                
                                                <td>{{ $incidence->getTypeasstring($incidence->type) }}</td>
                                                <td>
                                                    <span class="badge round-primary">{{ $incidence->sign_date }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('incidences.show', $incidence->id) }}" class="btn btn-success">Edit</a>
                                                    <a href="" onclick="event.preventDefault(); document.getElementById('delete-form-{{$incidence->id}}').submit();" class="btn btn-warning">Delete</a>

                                                    <form id="delete-form-{{$incidence->id}}" action="{{ route('incidences.destroy', $incidence->id) }}" method="POST" style="display: none;">
                                                          <input type="hidden" name="_method" value="delete">
                                                          @csrf
                                                    </form>
                                                </td>
                                            </tr>
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