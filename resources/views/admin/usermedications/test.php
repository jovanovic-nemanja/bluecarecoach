												<tbody>
				                                    <?php 
				                                    	foreach ($usermedications as $usermedication) { 
				                                    		if ($usermedication->assign_id == $assignmedication->id) { ?>
					                                    		<tr>
				                                    				<td colspan="4" class="hiddenRow" style="border-top: none; padding: 0!important;">
													                    <div class="accordian-body collapse" id="demo<?= $assignmedication->id ?>"> 
													                      	<table class="table table-striped">
														                        <thead>
														                          	<tr>
																						<th>Date</th>
																						<th>Name</th>
																						<th>Dose</th>
																						@if(auth()->user()->hasRole('care taker'))
																							<th>Actions</th>
																						@endif
																						@if(auth()->user()->hasRole('admin'))
																							<th></th>
																						@endif
														                          	</tr>
														                        </thead>
														                        <tbody>
														                          	<tr>
																						<td>{{ $usermedication->sign_date }}</td>
																						<td>{{ $medications->name }}</td>
																						<td>{{ $assignmedication->dose }}</td>
																						@if(auth()->user()->hasRole('care taker'))
													                                        <td>
													                                        	<a href="" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('delete-form-{{$usermedication->id}}').submit();">Delete</a>

													                                        	<form id="delete-form-{{$usermedication->id}}" action="{{ route('usermedications.destroy', $usermedication->id) }}" method="POST" style="display: none;">
																					                  <input type="hidden" name="_method" value="delete">
																					                  @csrf
																					            </form>
													                                        </td>
													                                    @endif

													                                    @if(auth()->user()->hasRole('admin'))
																							<td></td>
																						@endif
														                          	</tr>
														                        </tbody>
													                      	</table>
													                    </div>
												                  	</td>
												                </tr>
				                                    <?php } } ?>