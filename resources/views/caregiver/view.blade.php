@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Caregiver Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('caregiver.index')}}">Caregivers</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="ibox">
                    <div class="ibox-body text-center">
                        <div class="m-t-20"><?php
                            if(empty($user->profile_image)){ ?>
                                <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" /><?php
                            }else{ ?>
                                <img class="img-circle" style="height:150px;width: 150px;" src="<?php echo asset($user->profile_image); ?>" /><?php
                            }   ?> 
                        </div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ ucfirst($user->name) }}</h5>
                        <div class="m-b-20 text-muted">Caregiver</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Caregiver Details</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img">Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->name }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Contact No.</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->mobile_number }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Email Id</i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Address</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->location.", ".$user->city.", ".$user->state.", ".$user->country.", ".$user->zipcode  }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Services</i></div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php 
                                                if(empty($user->services)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->services as $srvc){
                                                        echo $count.". ".$srvc->title.",<br/> ";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Price Range</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ "$".$user->min_price." - $".$user->max_price }} </div>
                                        </div>
                                    </li>               
                                    <li class="media">
                                        <div class="media-img">Qualification</i></div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php 
                                                if(empty($user->qualification)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->qualification as $srvc){
                                                        echo $count.". ".$srvc->name.",<br/> ";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>                     
                                    <li class="media">
                                        <div class="media-img">Gender</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->gender }} </div>
                                        </div>
                                    </li>                                    
                                    <li class="media">
                                        <div class="media-img">Date of Birth</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($user->dob), 'd M, Y') }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Servicable Zipcodes</i></div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php 
                                                if(empty($user->service_zipcodes)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->service_zipcodes as $zip){
                                                        echo $count.". ".$zip->zip." (".$zip->city.")<br/>";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Non Servicable Zipcodes</i></div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php 
                                                if(empty($user->non_service_zipcodes)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->non_service_zipcodes as $zip){
                                                        echo $count.". ".$zip->zip." (".$zip->city.")<br/>";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Description</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->description }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Created At</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($user->created_at), 'd M, Y') }} </div>
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