@extends('layouts.appsecond', ['menu' => 'users'])

@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Title-->
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Users</h5>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Search Form-->
                    <div class="d-flex align-items-center" id="kt_subheader_search">
                        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total"><?= count($users); ?> Total</span>
                    </div>
                    <!--end::Search Form-->
                </div>
                <!--end::Details-->
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
                            <h3 class="card-label">Users</h3>
                        </div>
                        <div class="card-right d-flex align-items-center">
                            <input type="hidden" id="env_domain_url" value="{{ env('APP_URL') }}" />
                            
                            <select class="form-control looking_job mr-2" id="looking_job" required>
                                <option value="">Choose the status...</option>
                                <option value="1" <?php if($actived == 1){echo 'selected';} ?>>Looking for job</option>
                                <option value="2" <?php if($actived == 2){echo 'selected';} ?>>Not interested</option>
                            </select>

                            <button name="search" id="search" class="btn btn-success btn-sm form-control font-weight-bolder">Search</button>
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>License</th>
                                    <th>Looking For Job</th>
                                    <th>Experience</th>
                                    <th>ZipCode</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($users) {
                                        $i = 1;
                                        foreach($users as $user) { ?>
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>
                                                    {{ $user->firstname }}
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->license }}</td>
                                                <td>{{ ($user->looking_job == 1) ? "Looking for job" : "Not interested" }}</td>
                                                <td>{{ $user->care_giving_experience." Years" }}</td>
                                                <td>{{ $user->zip_code }}</td>
                                                <td>{{ $user->phone_number }}</td>
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
