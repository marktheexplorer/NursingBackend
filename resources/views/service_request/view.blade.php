@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Request Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('service_request.index')}}">Request</a></li>
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
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Request Details</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img">Client Name</div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->name }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Caregiver Name</div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php
                                                if(empty($final_caregivers)){
                                                    echo "NA";
                                                }else{
                                                    echo ucfirst($final_caregivers->name)." (".$final_caregivers->email.")<br/>";
                                                }?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Location</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->location.", ".$services->city.", ".$services->state.", ".$services->country.", ".$services->zip  }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Services</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->title }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Price Range</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ "$".$services->min_expected_bill." - $".$services->max_expected_bill }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Shift</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ substr_replace( $services->start_time, ":", 2, 0)." - ".substr_replace( $services->end_time, ":", 2, 0) }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Duration</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($services->start_date), 'd M, Y')." - ".date_format(date_create($services->start_date), 'd M, Y') }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Notes</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $services->description }} </div>
                                        </div>
                                    </li>
                                    @if(count($upload_docs) > 0)
                                        <li class="media">
                                            <div class="media-img">Upload Documents</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <table class="table table-striped table-bordered table-hover table-sm">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <td>#</td>
                                                                <td>Document</td>
                                                                <td>Date</td>
                                                                @if($services->status == 6)
                                                                    <td>Action</td>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody><?php
                                                            $count = 1;
                                                            foreach($upload_docs as $docs){ ?>
                                                                <tr>
                                                                    <td>{{ $count }}.</td>
                                                                    <td>
                                                                        <a href="{{ asset('/request_docs/'.$docs->value) }}" title="Download Document"> Download
                                                                            @if($count == 1 && $services->status > 6)
                                                                            {{ '(Approved)' }}
                                                                        @endif</a>
                                                                    </td>
                                                                    <td>{{ date_format(date_create($docs->created_at), 'd M, Y') }}</td>
                                                                    @if($count == 1 && $services->status == 6)
                                                                        <td>
                                                                            <a href="{{ url('admin/service_request/confirm_doc/'.$services->id) }}" class="btn-sm btn-success" title="Approved">Approved</a> &nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <a href="{{ url('admin/service_request/resendmail/'.$services->id) }}" class="btn-sm btn-primary" title="click here">Dis-approved</a>
                                                                        </td>
                                                                    @endif
                                                                    <td></td>
                                                                </tr><?php
                                                                $count++;
                                                            }?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    <li class="media">
                                        <div class="media-img">Status</i></div>
                                        <div class="media-body">
                                            <div class="media-heading"><?php
                                                switch($services->status){
                                                    case '0':
                                                        echo "Pending";
                                                        break;
                                                    case '1':
                                                        echo "Reject";
                                                        break;
                                                    case '2':
                                                        echo "Approved";
                                                        break;
                                                    case '3':
                                                        echo "Caregiver not Assign";
                                                        break;
                                                    case '4':
                                                        echo "Assign to Caregiver";
                                                        break;
                                                    case '5':
                                                        echo "Caregiver confirm and sent mail of basic careservice pack";
                                                        break;
                                                    case '6':
                                                        echo "Document upload by client, but document not varified";
                                                        break;
                                                    case '7':
                                                        echo "Uploaded document varified";
                                                        break;
                                                    case '8':
                                                        echo "Re-schedule";
                                                    case '9':
                                                        echo "Close";
                                                        break;
                                                } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img">Created At</i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ date_format(date_create($services->created_at), 'd M, Y') }} </div>
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
