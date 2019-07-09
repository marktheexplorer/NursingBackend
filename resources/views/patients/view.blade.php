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
                                       <strong>Email :</strong> {{ $user->email}}
                                    </li>
                                    <li class="media">
                                       <strong>Mobile Number :</strong> {{ $user->mobile_number}}
                                    </li>
                                    <li class="media">
                                       <strong>DOB :</strong> {{ $user->patient ? $user->patient->dob : ''}}
                                    </li>
                                    <li class="media">
                                       <strong>Gender :</strong> {{ $user->patient ? $user->patient->gender : ''}}
                                    </li>
                                    <li class="media">
                                       <strong>City :</strong> {{ $user->city}}
                                    </li>
                                    <li class="media">
                                       <strong>State :</strong> {{ $user->state}}
                                    </li>
                                    <li class="media">
                                      <strong>Country :</strong> {{ $user->country}}
                                    </li>
                                    <li class="media">
                                       <strong>Pin Code :</strong> {{ $user->patient ? $user->patient->pin_code : ''}}
                                    </li>
                                    <li class="media">
                                       <strong>Diagnosis :</strong> {{ $diagnosis ? $diagnosis->title : ''}}
                                    </li>
                                    <li class="media">
                                       <strong>Range :</strong> {{ $user->patient ? $user->patient->range : ''}}
                                    </li>
                                    <li class="media">
                                       <strong>Availability :</strong> {{ $user->patient ? $user->patient->availability : ''}}
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
