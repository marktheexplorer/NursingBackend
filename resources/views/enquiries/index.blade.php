@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Enquiries</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Enquiries</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Enquiries Data</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Id</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  		<th>Phone</th>
                            <th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Id</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  		<th>Phone</th>
                            <th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                 	@foreach($enquiries as $key => $enquiry)
	            		<tr>
	            			<td>{{ ++$key }}
	              			<td>{{ ucfirst($enquiry->name) }}</td>
	              			<td>{{ $enquiry->email }}</td>
	              			<td>{{ $enquiry->phone_number }}</td>
                            <td>{{ date_format(date_create($enquiry->created_at) , 'd M ,y') }}
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ route('enquiries.show',['id' => $enquiry->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
	              					<li>
	              						<form action="{{ url('/admin/enquiries/'.$enquiry->id) }}" method="POST" onsubmit="deleteEnquiry('{{ $enquiry->id }}', '{{ $enquiry->name }}', event,this)">
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

	function deleteEnquiry(id, name, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+name+" enquiry",
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
                swal("Cancelled", name+"'s enquiry will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
