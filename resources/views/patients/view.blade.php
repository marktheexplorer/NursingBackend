
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Client Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Client</a></li>
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
                                <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" />
                            @endif
                        </div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ ucfirst($user->name) }}</h5>
                        <h5 class="m-b-20 text-muted">Client</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Personal Information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img col-md-3">Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ ucfirst($user->f_name).' '.$user->m_name.' '.$user->l_name }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Email Id</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Mobile Number</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ '+'.$user->country_code.' '.substr_replace(substr_replace($user->mobile_number, '-', '3','0'), '-', '7','0') }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Date of Birth</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->dob }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Gender</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->gender ? $user->gender : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Height</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->height}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Weight</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->weight }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Language</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->language }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Street</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->street}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">County</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->city}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">State</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->state}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Zip Code</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->zipcode }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Alternate Contact Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->alt_contact_name }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Alternate Contact Number</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->alt_contact_no }}</div>
                                        </div>
                                    </li>
                                    @if($user->document)
                                    <li class="media">
                                        <div class="media-img col-md-3">Document Uploaded</div>
                                        <div class="media-body">
                                            <span><a href={{ asset('pdf/'.$user->document) }} target = "_blank"><i class="fas fa-file-pdf"></i></a></span>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i>Service Information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img col-md-3">Health Conditions</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $diagnosis ? $diagnosis->title : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Availability</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->availability : ''}}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Disciplines</div>
                                        <div class="media-body">
                                            <div class="media-heading"> <?php
                                            if($disciplines_name){
                                                foreach($disciplines_name as $key => $value){
                                                    if($value)
                                                        echo $value->name.',  ' ;
                                                    else
                                                        echo 'NA';
                                                }
                                            }?> </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Long Term Care insurance</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? ($user->patient->long_term == 1? 'Yes' : 'No'):'' }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img col-md-3">Pets</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? ($user->patient->pets == 1? 'Yes' : 'No'):'' }}</div>
                                        </div>
                                    </li>
                                    @if($user->patient?$user->patient->pets == 1:0)
                                    <li class="media">
                                        <div class="media-img col-md-3">Pets Description</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->patient ? $user->patient->pets_description :'NA' }}</div>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="media">
                                        <div class="media-img col-md-3">Additional Information</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->additional_info }}</div>
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
                            <p><h3 style="text-align: center;">Schedule</h3></p>
                            <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Booking Id</th>
                                        <th>Status</th>
                                        <th>Caregiver Assigned</th>
                                        <th>Location</th>
                                        <th>Services Type</th>
                                        <th>Schedule Date</th>
                                        <th>Schedule Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($services as $key => $service)
                                    <tr>
                                        <td>{{ "NUR".$service->id }}</td>
                                        <td>{{ $service->status }}</td>  
                                        <td>       
                                        @foreach ($service->caregivers as $key => $value) 
                                            @if($value->status == 'Final')
                                                {{ $value->caregiver->user->name .','}}
                                            @endif
                                        @endforeach
                                        </td>
                                        <td>{{ $service->address }} </td>
                                        <td>{{ $service->booking_type }} </td>
                                        <td>{{ $service->start_date .' - '. $service->end_date }}</td>
                                        <td>{{ $service->start_time .' - '. $service->end_time }}</td>
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
