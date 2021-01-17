@extends('layouts.app3d', ['menu' => 'residents'])

@section('content')
    <!-- START CONTENT -->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    	<!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container 3d">
            	<div class="card card-custom">
					<div id="container"></div>
					<button id="takeScreenshot" style="top: 10%; position: absolute; right: 2%;" class="btn btn-white">Screen Shot</button>

					<!-- modal start -->
			        <div class="modal fade col-xs-12" id="commentsModal">
			            <div class="modal-dialog">
			                <div class="modal-content">
			                	<form id="multipartform" enctype="multipart/form-data">
			                		@csrf

				                    <div class="modal-header">
				                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				                        <h4 class="modal-title">Body Harm</h4>
				                    </div>

				                    <div class="modal-body">
			                            <label class="form-label">Comment</label>
				                    	<select name="comment" id="comment" class="form-control comment">
				                    		
				                    	</select>

				                    	<input type="hidden" name="resident" value="{{ $resident }}" id="resident" class="resident">
				                    </div>
			                   	</form>
			                   	<div class="modal-footer">
			                   		<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				                   	<button type="button" id="shot" class="btn btn-info save_harm_prev">Submit</button>
			                   	</div>
			                </div>
			            </div>
			        </div>
			        <!-- modal end -->
			    </div>
		    </div>
		</div>
	</div>
	<!-- END CONTENT -->
@stop

@section('script')
	<script type="module" src="{{ asset('js/3d.js') }}"></script>
@endsection