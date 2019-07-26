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
                                        <div class="media-img">Email Id</div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Address</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->location.", ".$user->city.", ".$user->state.", ".$user->country.", ".$user->zipcode  }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Services</div>
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
                                        <div class="media-img">Price Range</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ "$".$user->min_price." - $".$user->max_price }} </div>
                                        </div>
                                    </li>               
                                    <li class="media">
                                        <div class="media-img">Qualification</div>
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
                                        <div class="media-img">Gender</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->gender }} </div>
                                        </div>
                                    </li>                                    
                                    <li class="media">
                                        <div class="media-img">Date of Birth</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($user->dob), 'd M, Y') }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Servicable Zipcodes</div>
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
                                        <div class="media-img">Non Servicable Zipcodes</div>
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
                                        <div class="media-img">Description</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->description }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Created At</div>
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

            <div class="col-md-12 col-lg-12" >   
                <div class="ibox">
                    <div class="ibox-body">
                        <div class="tab-content">
                            <p><h3>Requests Assigned</h3></p>
                            <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Service</th>
                                        <th>Patient</th>
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
                                        <th>Patient</th>
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