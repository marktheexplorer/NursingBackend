@extends('layouts.app')

@section('content')

<!-- start library for date and time picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css"/>
<link rel="stylesheet" href="{{ asset('admin/assets/material_datetimepicker/css/bootstrap-material-datetimepicker.css') }}" />
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{ asset('admin/assets/material_datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<!-- end library for date and time picker -->

<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Booking Edit</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Bookings</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Edit</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img col-md-3">Client Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ ucfirst($booking->user->name) }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Booking For</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->relation_id == '' ? 'Myself' :  $booking->relation->name .' - '. $booking->relation->relation->title }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Booking Type</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->booking_type }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Duration</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->start_date . ' - ' .$booking->end_date }}</div>
                                        </div>
                                    </li>
                                    @if( $booking->booking_type == 'Select from week' )
                                    <li class="media">
                                        <div class="media-img col-md-3">WeekDays</div>
                                        <div class="media-body">
                                            <div class="media-heading">{!! json_encode( implode(',' , unserialize($booking->weekdays))) !!}</div>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="media">
                                        <div class="media-img col-md-3">Timings</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->start_time . ' - ' .$booking->end_time }}
                                            @if( $booking->booking_type == 'Today' )
                                                &nbsp;&nbsp;&nbsp;<button onclick="$('#today_div').toggle();$(this).css('display','none');" class="form-control btn-sm btn-primary" style="max-width: 70px;padding:0px;display: inline;background-color: #3498db;background-image: none;" id="editbtn">Edit</button>
                                            @endif
                                            </div>
                                            <div id="today_div" style="display: none;">
                                                <form action="{{ route('bookings.today_update') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $booking->id }}" name="booking_id" />
                                                    <input type="text" id="todaystarttime" class="form-control floating-label" placeholder="Start Time" style="max-width: 120px;float: left; margin-right: 90px" name="todaystarttime">
                                                    <input type="text" id="todayendtime" class="form-control floating-label" placeholder="End Time" style="max-width: 120px;float: left; margin-right: 90px" disabled="true" name="todayendtime">
                                                    <script>
                                                        $('#todaystarttime').bootstrapMaterialDatePicker({ 
                                                            date: false,
                                                            format : 'HH:mm:ss'
                                                        }).on('change', function(e, date){
                                                            $('#todayendtime').bootstrapMaterialDatePicker('setMinDate', date);
                                                            $("#todayendtime").removeAttr("disabled");
                                                            $("#today_submit").css('display', 'none');
                                                        });

                                                        $('#todayendtime').bootstrapMaterialDatePicker({ 
                                                            date: false,
                                                            format : 'HH:mm:ss'
                                                        }).on('change', function(e, date){
                                                            $("#today_submit").css('display', 'inline');
                                                        });
                                                    </script>
                                                    <input class="form-control btn-sm btn-primary" style="max-width: 70px;padding:0px;display: none;background-color: #3498db;background-image: none;margin-right:22px;" type="submit" value="Submit" id="today_submit"/>
                                                    <button onclick="$('#today_div').toggle();$('#editbtn').css('display', 'inline');" class="form-control btn-sm btn-primary" style="max-width: 70px;padding:0px;display: inline;background-color: #3498db;background-image: none;" type="button">Cancel</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Height</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->height }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Weight</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->weight }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Date of Birth</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($booking->dob), 'd M, Y') }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Pets</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->pets}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Diagnosis</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $diagnosis }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Service Location</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->service_location->area}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Address</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->address }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">State</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->state}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Country</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->country}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Zipcode</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->zipcode}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Caregivers Assigned</div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                               @foreach($assignedCaregivers as $key => $caregiver) {{ $key+1 }}.  {{ $caregiver['name'] }}  ({{ $caregiver['email'] }})  <br> @endforeach</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12" >
                <div class="ibox">
                    <div class="ibox-body">
                        <div class="tab-content">
                            <p><h3 style="text-align: center;">Caregivers</h3></p>
                            <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone No.</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($caregivers as $key => $caregiver)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $caregiver->name }}</td>
                                        <td>{{ $caregiver->email }}</td>
                                        <td>{{ $caregiver->mobile_number }}</td>
                                        <td>
                                            <form action="{{ route('bookings.assign') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                            @csrf
                                                <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                                                <input type="hidden" name="caregiver_id" value="{{ $caregiver->caregiverId }}" />
                                                @if(in_array($caregiver->caregiverId, $assignedCaregiversId))
                                                <input type="checkbox" title="Un-Assign" checked class="form-control" style="cursor: pointer;" onclick="$(this).closest('form').submit();">
                                                @else
                                                <input type="checkbox" title="Assign" class="form-control" style="cursor: pointer;" onclick="$(this).closest('form').submit();">
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
</script>
@endsection
