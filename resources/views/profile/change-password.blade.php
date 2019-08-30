@extends('layouts.app')

@section('content')
<div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title">Change Password</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Change Password</li>
                </ol>
            </div>
            @include('flash::message')
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-md-10">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Change Password</div>
                            </div>
                            <div class="ibox-body">
                                
        						<form class="form-horizontal" method="POST" action="{{ route('update.password') }}">
        							@csrf
                                    <div class="form-group row pass_show">
                                        <label class="col-sm-4 col-form-label">Current Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" value="{{ old('current_password') }}" class="form-control {{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" placeholder="Current Password" required id="current_password">
							                @if ($errors->has('current_password'))
					        					<span class="invalid-feedback" role="alert" style="width:80%;float: left;">
					                				<strong>{{ $errors->first('current_password') }}</strong>
					            				</span>
					        				@endif 
                                            <span class="ptxt" style="cursor:pointer;float:right;color:#002e6d;" onclick="changepassword('current_password', 'ptxt1')" id="ptxt1">Show</span>
                                        </div>
                                    </div>
                                    <div class="form-group row pass_show">
                                        <label class="col-sm-4 col-form-label">New Password</label>
                                        <div class="col-sm-8">
                                         	<input type="password" value="{{ old('new_password') }}" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}"  name="new_password" placeholder="New Password" required id="new_password">
                                            @if ($errors->has('new_password'))
					        					<span class="invalid-feedback" role="alert" style="width:80%;float: left;">
					                				<strong>{{ $errors->first('new_password') }}</strong>
					            				</span>                                                
                                            @endif
                                            <span class="ptxt" style="cursor:pointer;float:right;color:#002e6d;" onclick="changepassword('new_password', 'ptxt2')" id="ptxt2">Show</span>
                                        </div>
                                    </div>
                                    <div class="form-group row pass_show">
                                        <label class="col-sm-4 col-form-label">Confirm Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" value="{{ old('new_password_confirmation') }}" class="form-control {{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name="new_password_confirmation" placeholder="Confirm Password" required id="new_password_confirmation">
							                @if ($errors->has('new_password_confirmation'))
					        					<span class="invalid-feedback" role="alert" style="width:80%;float: left;">
					                				<strong>{{ $errors->first('new_password_confirmation') }}</strong>
					            				</span>
					        				@endif
                                            <span class="ptxt" style="cursor:pointer;float:right;color:#002e6d;" onclick="changepassword('new_password_confirmation', 'ptxt3')" id="ptxt3">Show</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 ml-sm-auto" style="text-align: center;">
                                            <button class="btn btn-info" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            
<!-- <div id="content">
  	<div id="content-header">
    	<div id="breadcrumb"> 
    		<a href="{{ route('dashboard') }}" title="Go to Home" class="tip-bottom"><i class="fas fa-home"></i> Home</a> 
    		<a href="#" class="current">Change Password</a> 
    	</div>
    	<h1>Change Password</h1>
  	</div>
  	<div class="container-fluid">
	    <hr class="separate">
        @include('flash::message')
        <form method="POST" action="{{ route('update.password') }}">
    	@csrf 
		    <div class="row">
				<div class="col-sm-4">
			    	<label>Current Password</label>
				    <div class="form-group pass_show"> 
		                <input type="password" value="{{ old('current_password') }}" class="form-control {{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" placeholder="Current Password">
		                @if ($errors->has('current_password'))
        					<span class="invalid-feedback" role="alert">
                				<strong>{{ $errors->first('current_password') }}</strong>
            				</span>
        				@endif 
		            </div> 
			       	<label>New Password</label>
		            <div class="form-group pass_show"> 
		                <input type="password" value="{{ old('new_password') }}" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}"  name="new_password" placeholder="New Password">
		                @if ($errors->has('new_password'))
        					<span class="invalid-feedback" role="alert">
                				<strong>{{ $errors->first('new_password') }}</strong>
            				</span>
        				@endif  
		            </div> 
			       	<label>Confirm Password</label>
		            <div class="form-group pass_show"> 
		                <input type="password" value="{{ old('new_password_confirmation') }}" class="form-control {{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name="new_password_confirmation" placeholder="Confirm Password">
		                @if ($errors->has('new_password_confirmation'))
        					<span class="invalid-feedback" role="alert">
                				<strong>{{ $errors->first('new_password_confirmation') }}</strong>
            				</span>
        				@endif  
		            </div>
		            <div class="form-group">
		            	<button type="submit" class="btn btn-sm btn-success">Submit</button>
		            	<a href="{{ route('dashboard') }}" class="btn btn-sm btn-danger">Cancel</a>
		            </div> 
				</div> 	
			</div>
		</form> 
	</div>
</div> -->
@endsection
@section('footer-scripts')
<script type="text/javascript">
	$(document).ready(function(){
		//$('.pass_show div').append('<span class="ptxt" style="cursor:pointer;">Show</span>');  
	});

	function changepassword(inputid, selfid){
		$("#"+selfid).text($("#"+selfid).text() == "Show" ? "Hide" : "Show"); 
		$("#"+inputid).attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 
	}  
</script>
@endsection
