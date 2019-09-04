@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Requests Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Requests</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
    	<!--<a href="{{ route('caregiver.create') }}"><button class="btn btn-info "><i class="fas fa-plus"></i> Add</button></a> -->
        <div style="text-align: right;">
            <a href="{{ route('service_request.create') }}" style="" ><button class="btn btn-info "><i class="fas fa-plus"></i> Add Request</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('service_request.download_excel') }}" style="float:right;" target="_blank"><button class="btn btn-info "><i class="fas fa-download"></i> Download</button></a>
        </div>    
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Requests Data</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover table-sm table-responsive" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th style="min-width: 150px;">Patient</th>
                            <th style="min-width: 150px;">Caregiver</th>
                            <th style="min-width: 350px;">Service/Diagnos</th>
                            <th style="min-width: 350px;">Location</th>
                            <th style="min-width: 150px;">Price Range</th>
                            <th style="min-width: 100px;">Shift</th>
                            <th style="min-width: 165px;">Duration</th>
                            <th style="min-width: 165px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($services as $key => $srvc)
	            		<tr>
	            			<td>{{ ++$key }}.</td>
	              			<td>{{ ucfirst($srvc->name) }}</td>
                            <td><a href="{{ route('service_request.caregiver_list',['id' => $srvc->id]) }}" title="Select Caregiver">Change Caregiver</a></td>
                            <td>{{ ucfirst($srvc->title) }}</td>
	              			<td>{{ $srvc->location.", ".$srvc->city.", ".$srvc->state.", ".$srvc->zip }}</td>
                            <td><?php echo "$".$srvc->min_expected_bill." - $".$srvc->max_expected_bill; ?></td>
                            <td><?php echo substr_replace( $srvc->start_time, ":", 2, 0)." - ".substr_replace( $srvc->end_time, ":", 2, 0); ?></td>
	              			<td>{{ date_format(date_create($srvc->start_date), 'd M, Y')." - ".date_format(date_create($srvc->start_date), 'd M, Y')}}</td>
	              			<td>
	              				<ul class="actions-menu">
                                    <li>
                                        @if($srvc->status < 7)
    	              						<a href="{{ route('service_request.edit',['id' => $srvc->id]) }}">
    	              							<button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
    	              						</a>
                                        @else
                                            <a href="{{ route('service_request.reschedule',['id' => $srvc->id]) }}">
                                                <button class="btn-sm btn-primary btn-cir" title="Re-Schedule"><i class="fas fa-clock"></i></button>
                                            </a>
                                        @endif    
	              					</li>
	              					<li>
	              						<a href="{{ route('service_request.show',['id' => $srvc->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
	              					<li>
                                        <a href="{{ url('admin/service_request/blocked/'.$srvc->id) }}">
                                            @if($srvc->status)
                                                <button type="button" class="btn-sm btn-danger btn-cir" title="Unblock"><i class="fa fa-unlock"></i></button>
                                            @else
                                                <button type="button" class="btn-sm btn-success btn-cir" title="Block"><i class="fa fa-ban"></i></button>
                                            @endif
                                        </a>
                                    </li>    <!--
                                    <li>
	              						<form action="{{ url('/admin/caregiver/'.$srvc->id) }}" method="POST" onsubmit="deleteUser('{{ $srvc->id }}', '{{ $srvc->name }}', event,this)">
	                    				@csrf
	              							<button class="btn-sm btn-danger btn-cir" title="Delete"><i class="fas fa-trash-alt"></i></button>
	              						</form>
	              					</li>-->
	              				</ul>
	              			</td>
	            		</tr>
            		@endforeach
                    </tbody>
                </table>
            </div>
        </div>
	</div>    
</div>
@endsection

@section('footer-scripts')
<script type="text/javascript">
	$(document).ready( function () {
    	$('#data-table').DataTable();
	});

	function deleteUser(id, name, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+name+" user",
            icon: "warning",
            buttons: {
				cancel: true,
				confirm: true,
			},
            closeModal: false,
            closeModal: false,
            closeOnEsc: false,
        })
       .then((willDelete) => {
          	if (willDelete) {
                $.ajax({
                url: $(form).attr('action'),
                data: $(form).serialize(),
                type: 'DELETE',
                success: function(data) {
                    data = JSON.parse(data);
                    if(data['status']) {
                        swal({
                            title: data['message'],
                            text: "Press ok to continue",
                            icon: "success",
                            buttons: {
    							cancel: true,
    							confirm: true,
  							},
                            closeOnConfirm: false,
                            closeOnEsc: false,
                        })
                        .then((willDelete) => {
          					if (willDelete) {
                            	window.location.reload();
                            }
                            });
                        } else {
                             swal("Error", data['message'], "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", name+"'s user will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
