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
                        <div class="m-t-20">
                            @if(!empty($user->profile_image))
                               <img class="img-circle" src="{{ asset(config('image.user_image_url').$user->profile_image) }}" alt="No image">
                            @else
                                <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" />
                            @endif
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
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Personal Information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img   col-md-3">Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->name }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Mobile Number</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->mobile_number }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Email Id</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Address</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->location.", ".$user->city.", ".$user->state.", ".$user->zipcode  }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Language</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->language }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Gender</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->gender }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Height</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->height }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Weight</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->weight }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Date of Birth</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($user->dob), 'd M, Y') }} </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Service Information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img  col-md-3">Services</div>
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
                                        <div class="media-img  col-md-3">Price Range</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ "$".$user->min_price." - $".$user->max_price }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Discipline</div>
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
                                        <div class="media-img  col-md-3">Service Area</div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php
                                                if(empty($user->service_area)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->service_area as $row){
                                                        echo $count.". ".ucfirst($row->area)."<br/>";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Non Service Area</div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php
                                                if(empty($user->non_service_area)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($user->non_service_area as $row){
                                                        echo $count.". ".ucfirst($row->area)."<br/>";
                                                        $count++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Description</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->description }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img  col-md-3">Created At</div>
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
                            <p><h3 style="text-align: center;">Schedule</h3></p>
                            <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Client</th>
                                        <th>Location</th>
                                        <th>Service Type</th>
                                        <th>Schedule On</th>
                                        <th>Schedule Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($services) > 0)
                                    @foreach($services as $key => $service)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ ucfirst($service->user->name) }}</td>
                                            <td>{{ $service->address }} </td>
                                            <td>{{ $service->booking_type }}</td>
                                            <td>{{ $service->start_date . ' - ' . $service->end_date }}</td>
                                            <td>{{ $service->start_time . ' - ' . $service->end_time }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" style="text-align: center;"><strong>No record found.</strong></td>
                                    </tr>
                                @endif
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
