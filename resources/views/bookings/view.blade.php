@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Schedule Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Schedule</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
    @include('flash::message') 
    @if ($errors->has('caregiver_id'))
        <span class="invalid-feedback" role="alert" style="display:inline;">
            <strong>{{ $errors->first('caregiver_id') }}</strong>
        </span>
    @elseif($errors->has('caregiver_limit'))
        <span class="invalid-feedback" role="alert" style="display:inline;">
            <strong>{{ $errors->first('caregiver_limit') }}</strong>
        </span>
    @endif
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> {{ "Details "}} <b> {{ "#NUR".$booking->id }}</b></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img col-md-3">Client Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ ucfirst($booking->user->f_name).' '.$booking->user->m_name.' '.$booking->user->l_name }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Schedule For</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->relation_id == '' ? 'Myself' :  $booking->relation->name }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Schedule Type</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->booking_type }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Date</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->start_date . ' - ' .$booking->end_date }}</div>
                                        </div>
                                    </li>
                                    @if( $booking->booking_type == 'Select from week' )
                                    <li class="media">
                                        <div class="media-img col-md-3">WeekDays</div>
                                        <div class="media-body">
                                            <div class="media-heading">{!! json_encode( implode(', ' , unserialize($booking->weekdays))) !!}</div>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="media">
                                        <div class="media-img col-md-3">Shift(s)</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $booking->start_time . ' - ' .$booking->end_time }}</div>
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
                                            <div class="media-heading">{{ empty($diagnosis) ? '' : $diagnosis }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Services</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ empty($services) ? '' : $services }}</div>
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
            @if($booking->status == 'Pending' || $booking->status == 'Caregiver Request')
           
            <div class="col-md-12 col-lg-12" > 
                <form action="{{ route('bookings.assign') }}" method="post" class="" enctype="multipart/form-data">
                @csrf
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Caregivers</div>
                            <div class="col-md-4 float-right">
                                <label class="">Caregiver Limit:</label>
                                <input type="number" max="5" min="1" name="caregiver_limit" value="{{ $booking->caregiver_limit ? $booking->caregiver_limit : '' }}" required>
                            </div>
                        </div>                    
                        <div class="ibox-body">
                            <div class="tab-content">
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
                                                <td>{{ $caregiver->f_name.' '.$caregiver->m_name.' '.$caregiver->l_name }}</td>
                                                <td>{{ $caregiver->email }}</td>
                                                <td>{{ $caregiver->mobile_number != '' ? '+'.$caregiver->country_code.' '.substr_replace(substr_replace($caregiver->mobile_number, '-', '3','0'), '-', '7','0') : '' }}</td>
                                                <td>
                                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                                                    @if(in_array($caregiver->caregiverId, $assignedCaregiversId))
                                                    <input type="checkbox" title="Un-Assign" checked class="form-control" style="cursor: pointer;" name="caregiver_id[]" value="{{ $caregiver->caregiverId }}" >
                                                    @else
                                                    <input type="checkbox" title="Assign" class="form-control" style="cursor: pointer;" name="caregiver_id[]" value="{{ $caregiver->caregiverId }}" >
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button class="form-control btn btn-info" onclick="$(this).closest('form').submit();">Submit</button> 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif
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
