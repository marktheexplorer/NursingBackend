@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Contact Us Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Contact Us</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Contact Us</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  		<th>Phone</th>
                            <th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($contactUs as $key => $contact)
	            		<tr  @if($contact->is_read == 0) style="background-color: #cec4c4" @endif>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($contact->user->f_name).' '.$contact->user->m_name.' '.$contact->user->l_name }}</td>
	              			<td>{{ $contact->user->email }}</td>
	              			<td>{{ $contact->user->mobile_number }}</td>
                            <td>{{ date_format(date_create($contact->created_at) , 'd M ,y') }}
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ route('contactUs.show',['id' => $contact->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
	              					<li>
	              						<form action="{{ url('/admin/contactUs/'.$contact->id) }}" method="POST" onsubmit="deleteContact('{{ $contact->id }}', '{{ $contact->name }}', event,this)">
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

	function deleteContact(id, name, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+name+" contact us details",
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
                swal("Cancelled", name+"'s contact us details will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
