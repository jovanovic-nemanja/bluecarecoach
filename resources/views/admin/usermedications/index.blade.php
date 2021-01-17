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
                        	<a href="{{ route('usermedications.indexusermedication', $user->id) }}">
			            		<h3 class="card-label" style="cursor: pointer; color: #4d9cf8; font-weight: bold;">Assigned Medications</h3>
			            	</a>
			                <a href="{{ route('usermedications.indexusermedicationgiven', $user->id) }}">
			                	<h3 class="card-label" style="cursor: pointer;">Given Medications</h3>
			                </a>
                        </div>
                        <div class="card-toolbar">
                        	@if(auth()->user()->hasRole('admin'))
		                		<a href="{{ route('usermedications.createassignmedication', $user->id) }}" class="btn btn-primary font-weight-bolder">Assign</a>
							@endif
                        </div>
                    </div>
                        
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Dose</th>
                                    <th>Time</th>
                                    <th>Route</th>
                                    @if(auth()->user()->hasRole('care taker'))
										<th>Comment</th>
									@endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php 
                            		if(@$arrs) {
                                		$i = 1;
	                                	foreach($arrs as $assignmedication) { 
	                                		$flag = App\Usermedications::getassignedMedication($assignmedication['id']);
	                                		if ($flag == 1) { ?>
		                                		<tr role='row' data-toggle="collapse" data-target="#demo<?= $assignmedication['id'] ?>" class="odd accordion-toggle">
		                                			<?php 
	                                                	$medications = App\Assignmedications::getMedications($assignmedication['id']);
	                                                ?>

		                                			<td>{{ $i }}</td>
		                                			<td>{{ $assignmedication->start_day }} ~ {{ $assignmedication->end_day }}</td>
		                                			<td>{{ $medications->name }}</td>
			                                        <td>
			                                            <div class="">
			                                                <h6>{{ $assignmedication['dose'] }}</h6>
			                                            </div>
			                                        </td>
			                                        <td>
			                                        	<span class="badge round-primary">{{ $assignmedication['time'] }}</span>
			                                        </td>
			                                        <td>
														<span class="badge round-primary">
															<?php if($assignmedication['route'] != NULL) { ?>
																{{ App\Routes::getRoutename($assignmedication['route']) }}
															<?php } ?>
														</span>
			                                        </td>
			                                        
		                                        	@if(auth()->user()->hasRole('care taker'))
			                                        	<td>
															<select class="form-control" id="comment" name="comment">
																<option value="">Choose Comment</option>
			                                                    @foreach($comments as $comment)
			                                                        <option value="{{ $comment->id }}">{{ $comment->name }}</option>
			                                                    @endforeach
															</select>
														</td>
													@endif
													
			                                        <td>
				                                        @if(auth()->user()->hasRole('admin'))
				                                        	<a href="{{ route('usermedications.showassign', $assignmedication['id']) }}" class="btn btn-success">Edit</a>
				                                        	<a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('delete-form-{{$assignmedication['id']}}').submit();">Delete</a>

				                                        	<form id="delete-form-{{$assignmedication['id']}}" action="{{ route('usermedications.destroyassign', $assignmedication['id']) }}" method="POST" style="display: none;">
											                  	<input type="hidden" name="_method" value="delete">
											                  	@csrf
												            </form>


												            <a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('give-medication-form-{{$assignmedication['id']}}').submit();">Give Medication</a>

															<form id="give-medication-form-{{$assignmedication['id']}}" action="{{ route('usermedications.store') }}" method="POST" style="display: none;">
											                  	<input type="hidden" name="_method" value="POST">
											                  	@csrf

											                  	<input type="hidden" name="resident" value="{{ $user->id }}">
											                  	<input type="hidden" name="comment" class="comm_val" />
                                								<input type="hidden" name="assign_id" value="{{ $assignmedication['id'] }}">
                                								<input type="hidden" name="type" value="{{ $assignmedication['type'] }}">
												            </form>


												            <a href="" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('stop-activity-form-{{$assignmedication->id}}').submit();">Stop</a>

															<form id="stop-activity-form-{{$assignmedication->id}}" action="{{ route('usermedications.stop') }}" method="POST" style="display: none;">
											                  	<input type="hidden" name="_method" value="POST">
											                  	@csrf

											                  	<input type="hidden" name="resident" value="{{ $user->id }}">
                                								<input type="hidden" name="medication_id" value="{{ $assignmedication->id }}">
												            </form>
					                                    @endif
					                                    @if(auth()->user()->hasRole('care taker'))
															<a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('give-medication-form-{{$assignmedication['id']}}').submit();">Give Medication</a>

															<form id="give-medication-form-{{$assignmedication['id']}}" action="{{ route('usermedications.store') }}" method="POST" style="display: none;">
											                  	<input type="hidden" name="_method" value="POST">
											                  	@csrf

											                  	<input type="hidden" name="resident" value="{{ $user->id }}">
											                  	<input type="hidden" name="comment" class="comm_val" />
                                								<input type="hidden" name="assign_id" value="{{ $assignmedication['id'] }}">
                                								<input type="hidden" name="type" value="{{ $assignmedication['type'] }}">
												            </form>
														@endif
													</td>
			                                    </tr>
                                <?php $i++; } } }else{ ?>

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
    $(document).ready(function(){
        $('#comment').change(function() {
        	var com_val = $(this).val();
        	$('.comm_val').val(com_val);
        });
    });
</script>
@endsection