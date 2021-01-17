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
                <!--begin::Row-->
                
                <?php if(count($users) > 0) { ?>
                    <div class="row">
                        @foreach($users as $user)
                            <!--begin::Col-->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <!--begin::Card-->
                                <div class="card card-custom gutter-b card-stretch">
                                    <!--begin::Body-->
                                    <div class="card-body pt-4">
                                        <!--begin::Toolbar-->
                                        <div class="d-flex justify-content-end">
                                            <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            </div>
                                        </div>
                                        <!--end::Toolbar-->
                                        <!--begin::User-->
                                        <div class="d-flex align-items-end mb-7">
                                            <!--begin::Pic-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Pic-->
                                                <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                                                    <div class="symbol symbol-circle symbol-lg-75">
                                                        <!-- <a href="{{ route('resident.show', $resident->id) }}"> -->
                                                            <img src="{{ asset('uploads/').'/'.$user->profile_logo }}" class="rad-50 center-block custom_img_tag" alt="image">
                                                        <!-- </a> -->
                                                    </div>
                                                    <div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
                                                        <span class="font-size-h3 font-weight-boldest">JM</span>
                                                    </div>
                                                </div>
                                                <!--end::Pic-->
                                                <!--begin::Title-->
                                                <div class="d-flex flex-column">
                                                    <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0 custom_a_tag">{{ $user->firstname }}</a>
                                                    <span class="text-muted font-weight-bold">User</span>
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Desc-->
                                        <p class="mb-7"><a href="#" class="text-primary pr-1">{{ App\User::getGender($user->gender) }} / {{ $user->birthday }}</a></p>
                                        <!--end::Desc-->
                                        <!--begin::Info-->
                                        <div class="mb-7">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-dark-75 font-weight-bolder mr-2">Email:</span>
                                                <a href="#" class="text-muted text-hover-primary custom_a_tag">{{ $user->email }}</a>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-cente my-1">
                                                <span class="text-dark-75 font-weight-bolder mr-2">Phone:</span>
                                                <a href="#" class="text-muted text-hover-primary custom_a_tag">{{ $user->phone_number }}</a>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-dark-75 font-weight-bolder mr-2">Location:</span>
                                                <span class="text-muted font-weight-bold">{{ $user->street1 }}</span>
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end::Col-->
                        @endforeach
                    </div>
                <?php } else { ?>
                    <div style="text-align: center;">
                        <br><br>
                        <h6>There is no resident at this moment</h6>
                    </div>
                <?php } ?>        

                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@stop
