@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Select Caregiver for Request</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('service_request.index')}}">Request</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Choose Caregivers</a>
                            </li>
                        </ul>
                        @include('flash::message')
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img">Patient Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->name }} </div>
                                        </div>
                                        <div class="media-img">Price Range</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ "$".$services->min_expected_bill." - $".$services->max_expected_bill }} </div>
                                        </div>
                                        <div class="media-img">Duration</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($services->start_date), 'd M, Y')." - ".date_format(date_create($services->start_date), 'd M, Y') }}</div>
                                        </div>
                                    </li>
                                    <li class="media">                                        
                                        <div class="media-img">Location</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->location.", ".$services->city.", ".$services->state.", ".$services->country.", ".$services->zip  }}</div>
                                        </div>
                                    </li>
                                    <li class="media">    
                                        <div class="media-img">Service</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ ucfirst($services->title) }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Assign Caregivers</div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php
                                                if(empty($final_caregivers)){
                                                    echo "NA";
                                                }else{
                                                    $count = 1;
                                                    foreach($final_caregivers as $user){
                                                        echo $count.". ".ucfirst($user->name)." (".$user->email.")<br/>";
                                                    }
                                                }?>    
                                            </div>
                                        </div>
                                    </li><!--
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="media-heading"><input type="text" name="searchby['keyword']" value="" placeholder="Search..." class="" style="padding:3px;" /></div>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading"><label> <input type="checkbox" name="searchby['service']" value="{{ $services->service }}"> {{ $services->title }} </label></div>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading"><label> <input type="checkbox" name="searchby['zip']" value="{{ $services->zip }}"> {{ $services->zip }} </label></div>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading"><button class="btn btn-default pull-right" type="submit">Submit</button></div>
                                        </div>
                                    </li>    --> 
                                    <li class="media caregiverlist"><br/><br/><?php
                                        $count = 0; ?>
                                        @foreach($caregivers as $key => $user)<!-- 
                                            <div class="media-body" style="padding: 20px;border: 1px solid #072651;border-radius: 10px;margin-right: 25px;min-width: 320px;">
                                                <label> 
                                                    <input type="checkbox" name="searchby['service']" value="{{ $services->service }}"></i><br/><?php
                                                    if(empty($user->profile_image)){ ?>
                                                        <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" /><?php
                                                    }else{ ?>
                                                        <img class="img-circle" style="height:150px;width: 150px;" src="<?php echo asset($user->profile_image); ?>" /><?php
                                                    }   ?><br/><hr style="background-color: #000;" />
                                                    Name : {{ ucfirst($user->name) }}<br/>
                                                    Service : {{ ucfirst($user->title) }}<br/>
                                                    Email : {{ $user->email }}<br/>
                                                    Contact No. : {{ $user->mobile_number }}
                                                </label>
                                            </div> -->
                                        @endforeach
                                        <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile no</th>
                                                    <th>Service</th>
                                                    <th>Zip Code</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile no</th>
                                                    <th>Service</th>
                                                    <th>Zip Code</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            @foreach($caregivers as $key => $user)
                                                <tr>
                                                    <td>{{ ++$key }}.</td>
                                                    <td>{{ ucfirst($user->name) }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->mobile_number }}</td>
                                                    <td>{{ ucfirst($user->title) }}</td>    
                                                    <td>{{ ucfirst($user->zipcode) }}</td>
                                                    <td>
                                                        <form action="{{ route('service_request.assign') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="request_id" value="{{ $services->id }}" />
                                                            <input type="hidden" name="caregiver_id" value="{{ $user->id }}" />
                                                            @if(in_array($user->id, $select_caregiver))
                                                                <button type="submit" class="btn-sm btn-danger btn-cir" title="Un-Assign"><i class="fas fa-times-circle"></i></button>
                                                            @else
                                                                <button type="submit" class="btn-sm btn-success btn-cir" title="Assign"><i class="fas fa-check-circle"></i></button>
                                                            @endif
                                                        </form>    
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
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
@section('footer-scripts')
<script type="text/javascript">
    $(document).ready( function () {
        $('#data-table').DataTable();
    });
</script>    
@endsection