@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Schedule</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index')}}" >All Schedule</a></li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Schedule Data</div>
                <form method="get">    
                    <select name="booking_options" class="form-control" style="float: right;width:200px;" onchange="$(this.form).submit();">
                        <option disabled="" selected=> -- Select Schedule Type --</option>
                        @foreach($booking_type as $key => $type)
                            <option value="{{ $type['booking_type'] }}" <?php echo ($select_booking_type == $type['booking_type'] ? 'selected' : ''); ?> >{{ ucfirst($type['booking_type']) }}</option>
                        @endforeach
                    </select>
                </form>    
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Client</th>
                            <th>Schedule ID</th>
	                  		<th>Mobile Number</th>
	                  		<th>Status</th>
	                  		<th>Schedule Type</th>
	                  		<th>Created At</th>
	                  		<th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($bookings as $key => $booking)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	            			<td>{{ ucfirst($booking->user->name) }}</td>
                            <td>{{ "NUR".$booking->id }}</td>
	              			<td>{{ $booking->user->mobile_number != '' ? substr_replace(substr_replace($booking->user->mobile_number, '-', '3','0'), '-', '7','0') : '' }}</td>
	              			<td>{{ $booking->status }}</td>
	              			<td>{{ $booking->booking_type }}</td>
	              			<td>{{ date_format(date_create($booking->created_at), 'd M, y')}}</td>
	              			<td>
                                <ul style="display: inline;">
                                    <li class="media-list media-list-divider m-0" style="float: left;padding-right:5px;">
                                        <a href="{{ route('bookings.show',['id' => $booking->id]) }}">
                                            <button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
                                        </a>
                                    </li>
                                    <li class="media-list media-list-divider m-0" style="float: left;padding-right:5px;">
                                        <a href="{{ route('bookings.show',['id' => $booking->id]) }}">
                                            <button class="btn-sm btn-secondary btn-cir" title="Manage Caregivers"><i class="fas fa-tasks"></i></button>
                                        </a>
                                    </li>
                                    <li class="media-list media-list-divider m-0" style="float: left;padding-right:5px;">
                                        @if($booking->booking_type == 'Select date')
                                            <a href="{{ route('bookings.select_date_form',['id' => $booking->id]) }}">
                                                <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                            </a>
                                        @elseif($booking->booking_type == 'Select from week')
                                            <a href="{{ route('bookings.select_from_week_form',['id' => $booking->id]) }}">
                                                <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                            </a>
                                        @elseif($booking->booking_type == 'Daily')
                                            <a href="{{ route('bookings.daily_form',['id' => $booking->id]) }}">
                                                <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                            </a>
                                        @endif
                                    </li>
                                    @if($booking->status != "Completed") 
                                        <li class="media-list media-list-divider m-0" style="float: left;padding-right:5px;">
                                            <form action="{{ route('bookings.complete_booking',['id' => $booking->id]) }}" method="GET"  onsubmit="markascomplete('{{ $booking->id }}', event,this)">
                                                <button class="btn-sm btn-info btn-cir" title="Mark as Completed"><i class="fas fa-check"></i></button>
                                            </form>
                                        </li>
                                    @endif
                                    <li class="media-list media-list-divider m-0" style="float: left;padding-right:5px;">
                                        <form action="{{ route('bookings.delete',['id' => $booking->id]) }}" method="DELETE" onsubmit="deleteBooking('{{ $booking->id }}', event,this)">
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

    function markascomplete(id, event,form){
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to mark as completed #NUR"+id+" Schedule",
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
                type: 'GET',
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
                swal("Cancelled", "#NUR"+id+" Schedule will not be mark as completed.", "error");
            }
        });
    }

    function deleteBooking(id, event,form){
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete #NUR"+id+" Schedule",
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
                swal("Cancelled", "#NUR"+id+" schedule will not be deleted.", "error");
            }
        });
    }
</script>
@endsection