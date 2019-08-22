@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Patient Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="ibox">
                    <div class="ibox-body text-center">
                        <div class="m-t-20">
                            @if(!empty($user->profile_image))
                               <img class="img-circle" src="{{ asset(config('image.user_image_url').$user->profile_image) }}" alt="No image"> 
                            @else
                                No image uploaded.
                            @endif 
                        </div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ ucfirst($user->name) }}</h5>
                        <h5 class="m-b-20 text-muted">Patient</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <div class="tab-content">
                            <div class="tab-pane fade @if(count($errors) > 0) '' @else show active @endif" id="tab-1">
                                <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Details</h5>
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img">Email Id</div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Mobile Number</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->mobile_number }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Date of Birth</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($user->dob), 'd M, Y') }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Gender</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->gender ? $user->gender : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">City</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->city}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">State</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->state}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Country</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->country}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Zip Code</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->pin_code : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Diagnosis</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $diagnosis ? $diagnosis->title : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Range</div>
                                        <div class="media-body">
                                            <div class="media-heading">${{ $user->patient ? $user->patient->range : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Availability</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->availability : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Disciplines</div>
                                        <div class="media-body">
                                            <div class="media-heading"> <?php foreach($disciplines_name as $key => $value){ echo $value->name.',  ' ;} ?> </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Long Term Care insurance</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? ($user->patient->long_term == 1? 'Yes' : 'No'):'' }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Pets</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? ($user->patient->pets == 1? 'Yes' : 'No'):'' }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Pets Description</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->pets_description :'' }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Additional Information</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->additional_info : ''}}</div>
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
                            <p><h3>Services Requested</h3></p>
                            <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Service</th>
                                        <th>Caregiver Assigned</th>
                                        <th>Price Range</th>
                                        <th>Location</th>
                                        <th>Shift</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Service</th>
                                        <th>Caregiver Assigned</th>
                                        <th>Price Range</th>
                                        <th>Location</th>
                                        <th>Shift</th>
                                        <th>Created At</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @foreach($services as $key => $service)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ ucfirst($service->title) }}</td>
                                        <td>{{ ucfirst($service->name) }}</td>
                                        <td>{{ "$".$service->min_expected_bill ."- $". $service->max_expected_bill }}</td>
                                        <td>{{ $service->location.", ".$service->city.", ".$service->state.", ".$service->country.", ".$service->zip  }} </td>
                                        <td>{{ substr_replace( $service->start_time, ":", 2, 0)." - ".substr_replace( $service->end_time, ":", 2, 0) }}</td>
                                        <td>{{ date_format(date_create($service->created_at), 'd M, y')}}</td>
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

