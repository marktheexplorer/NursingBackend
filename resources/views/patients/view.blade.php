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
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
