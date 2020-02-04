@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Caregiver Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Caregivers</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
    	<div style="text-align: right;">
            <a href="{{ route('caregiver.create') }}"><button class="btn btn-info "><i class="fas fa-plus"></i> Add</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('caregiver.download_excel') }}" style="" target="_blank"><button class="btn btn-info "><i class="fas fa-download"></i> Download</button></a>
        </div>
        <div class="ibox">
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  	  	<th>Mobile Number</th>
                            <th>Price Range</th>
                            <th>Disciplines</th>
                            <th>Language</th>
	                  		<th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                 	@foreach($caregivers as $key => $user)
	            		<tr>
                            <td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($user->f_name).' '.$user->m_name.' '.$user->l_name }}</td>
	              			<td>{{ $user->email }}</td>
	              			<td>{{ $user->mobile_number != '' ? substr_replace(substr_replace($user->mobile_number, '-', '3','0'), '-', '7','0') : '' }}</td>
                            <td>{{ "$".$user->min_price."-$".$user->max_price }}</td>
                            <td>{{ implode(' , ',$user->qualification) }}</td>
                            <td>{{ $user->language }}</td>
	              			<td>{{ date_format(date_create($user->created_at), 'd M, Y')}}</td>
	              			<td>
	              				<ul class="actions-menu" style="padding-left: 0px;">
                                    <li>
                                    <a href="{{ url('admin/caregiver/blocked/'.$user->id) }}">
                                        @if($user->is_blocked)
                                            <button type="button" class="btn-sm btn-danger btn-cir" title="Block"><i class="fas fa-lock"></i></button>
                                        @else
                                            <button type="button" class="btn-sm btn-success btn-cir" title="Unblock"><i class="fas fa-lock-open"></i></button>
                                        @endif
                                    </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('caregiver.edit',['id' => $user->id]) }}">
                                            <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                        </a>
                                    </li>
	              					<li>
	              						<a href="{{ route('caregiver.show',['id' => $user->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
                                    <li>
                                        <form action="{{ route('caregiver.destroy',['id' => $user->id]) }}" method="POST" onsubmit="deleteCaregiver('{{ $user->id }}', '{{ $user->name }}', event,this)">
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

	function deleteCaregiver(id, title, event,form)
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
