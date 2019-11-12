@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Bookings</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index')}}" >Bookings</a></li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Bookings Data</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Client</th>
	                  		<th>Mobile Number</th>
	                  		<th>Status</th>
	                  		<th>Booking Type</th>
	                  		<th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($bookings as $key => $booking)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	            			<td>{{ ucfirst($booking->user->name) }}</td>
	              			<td>{{ $booking->user->mobile_number }}</td>
	              			<td>{{ $booking->status }}</td>
	              			<td>{{ $booking->booking_type }}</td>
	              			<td>{{ date_format(date_create($booking->created_at), 'd M, y')}}</td>
	              			<td>
                                <ul>
                                    <li class="media-list media-list-divider m-0">
                                        <a href="{{ route('bookings.show',['id' => $booking->id]) }}">
                                            <button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
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
