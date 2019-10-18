@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Client Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index')}}" >Client</a></li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Client Data</div>
                <div>
                    <a href="{{ route('patients.active') }}"><button class="btn btn-primary">Active Client</button></a>
                    <a href="{{ route('patients.inactive') }}"><button class="btn btn-primary">Inactive Client</button></a>
                </div>
                <div>
                    <a href="{{ route('patients.create') }}"><button class="btn btn-info"><i class="fas fa-plus"></i> Add</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('patients.download_excel') }}" style="" target="_blank"><button class="btn btn-info "><i class="fas fa-download"></i> Download</button></a>
                </div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  		<th>Mobile Number</th>
	                  		<th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($patients as $key => $patient)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($patient->name) }}</td>
	              			<td>{{ $patient->email }}</td>
	              			<td>{{ $patient->mobile_number }}</td>
	              			<td>{{ date_format(date_create($patient->created_at), 'd M, y')}}</td>
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ url('admin/patients/blocked/'.$patient->id) }}">
					                    @if($patient->is_blocked)
					                    	<button type="button" class="btn-sm btn-danger btn-cir" title="Block"><i class="fas fa-lock"></i></i></button>
					                	@else
					                		<button type="button" class="btn-sm btn-success btn-cir" title="Unblock"><i class="fas fa-lock-open"></i></button>
					            		@endif
                                        </a>
	              					</li>
	              					<li>
	              						<a href="{{ route('patients.edit',['id' => $patient->id]) }}">
	              							<button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
	              						</a>
	              					</li>
	              					<li>
	              						<a href="{{ route('patients.show',['id' => $patient->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
                                    <li>
                                        <form action="{{ route('patients.destroy',['id' => $patient->id]) }}" method="POST" onsubmit="deletePatient('{{ $patient->id }}', '{{ $patient->name }}', event,this)">
                                        @csrf
                                            <button class="btn-sm btn-danger btn-cir" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </li>
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

    function deletePatient(id, title, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+title+" user",
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
                swal("Cancelled", title+" user will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
