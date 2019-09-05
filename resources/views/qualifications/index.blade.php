@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Discipline Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Disciplines</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Discipline Data</div>
                <a href="{{ route('qualifications.create') }}"><button class="btn btn-info pull-right"><i class="fas fa-plus"></i> Add</button></a>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Title</th>
	                  		<th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($qualifications as $key => $qualification)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($qualification->name) }}</td>
	              			<td>{{ date_format(date_create($qualification->created_at), 'd M, Y')}}</td>
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ route('qualifications.edit',['id' => $qualification->id]) }}">
	              							<button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
	              						</a>
	              					</li>	              					
                                    <li>
                                        <a href="{{ url('admin/qualifications/blocked/'.$qualification->id) }}">
                                        @if($qualification->is_blocked)
                                            <button type="button" class="btn-sm btn-danger btn-cir" title="Unblock"><i class="fas fa-lock"></i></button>
                                        @else
                                            <button type="button" class="btn-sm btn-success btn-cir" title="Block"><i class="fas fa-lock-open"></i></button>
                                        @endif
                                        </a>
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

	function deleteService(id, title, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+title+" service",
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
                swal("Cancelled", title+" discipline will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
