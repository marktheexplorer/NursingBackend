@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Inquiry Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}" >Inquiry</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link @if(count($errors) == 0) active @endif " href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Details</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade @if(count($errors) > 0) '' @else show active @endif" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                	<li class="media">
                                        <div class="media-img"><i class="far fa-user"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $enquiry->name }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-envelope"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $enquiry->email }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-phone-volume"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $enquiry->phone_number }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-comment-alt"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $enquiry->message }} </div>
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
<div id="content">
  	<div id="content-header">
    	<div id="breadcrumb"> 
    		<a href="{{ route('dashboard') }}" title="Go to Home" class="tip-bottom"><i class="fas fa-home"></i> Home</a> 
    		<a href="{{ route('enquiries.index') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Enquiries</a> 
    		<a href="#" class="current">View Enquiry</a> 
    	</div>
    	<h1>Enquiry Details</h1>
  	</div>
  	<div class="container-fluid">
	    <hr>
	    <div class="row-fluid">
	      	<div class="span12">
	        	<div class="widget-box">
	          		<div class="widget-title"> <span class="icon"> <i class="fas fa-list-alt"></i> </span>
	           			 <h5>Details</h5>
	          		</div>
	          		<div class="widget-content"> 
	      				<table class="table table-bordered table-invoice">
		                  	<tbody>
		                    	<tr>
			                      	<td class="width30">Name :</td>
		                  			<td class="width70"><strong>{{ $enquiry->name }}</strong></td>
		                    	</tr>
		                    	<tr>
		                      		<td>Phone Number :</td>
		                      		<td><strong>{{ $enquiry->phone_number }}</strong></td>
		                    	</tr>
		                    	<tr>
		                      		<td>Email :</td>
		                      		<td><strong>{{ $enquiry->email }}</strong></td>
		                    	</tr>
		                  		<tr>
		                  			<td class="width30">Message :</td>
		                    		<td class="width70">{{ $enquiry->message }}</td>
		                  		</tr>
		                    </tbody>
	                	</table>
	          		</div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>
@endsection
