@extends('layouts.default')
@section('content')
	<div class="jumbotron text-center pb-1">
		<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;">
		<div class="container">
			<div class="row">
				<div class="col-md-12"><br/><br/><br/>
					@if(!empty($user))
						<h1>Reset Password</h1>	
		                <form action="{{ env('APP_URL') }}caregiver/savepassword" method="post" class="form-horizontal">
		                @csrf
		                	@include('flash::message')
		                	<input type="hidden" name="token" value="{{ print_r($user->email_activation_token) }}">
		                	<table class="table" id="data-table" cellspacing="0" width="100%">
		                		<tr>
		                			<td>
		                				<label>Password</label>
		                			</td>
		                			<td>
		                				<input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" value="{{ old('password') }}"/>
			                            @if ($errors->has('password'))
			                                <span class="text-danger">
			                                    <strong>{{ $errors->first('password') }}</strong>
			                                </span>
			                            @endif
		                			</td>
		                		</tr>
		                		<tr>
		                			<td>
		                				<label>Confirm Password</label>
		                			</td>
		                			<td>
		                				<input type="password" class="form-control {{ $errors->has('cpassword') ? ' is-invalid' : '' }}" name="cpassword" placeholder="Confirm Password" value="{{ old('cpassword') }}"/>
			                            @if ($errors->has('cpassword'))
			                                <span class="text-danger">
			                                    <strong>{{ $errors->first('cpassword') }}</strong>
			                                </span>
			                            @endif
		                			</td>
		                		</tr>
		                		<tr>
		                			<td colspan="2">
		                				<input type="submit" value="Set Password" class="btn btn-default" type="submit" style="padding:10px;border:1px solid; background-color: #002e6d;color:#fff;border-radius: 3px;" />
		                			</td>
		                		</tr>
		                	</table><br/><br/><br/><br/>
		                </form>
		            @elseif(isset($issuccess))
		            	<h1>Congrates !!!<br/><br/> Your reset password successfully<br/><br/> Now you can login with your new Password</h1><br/><br/><br/>
		            @else
		            	<h1>Ooops<br/><br/> Look like your password reset link in un-authorized<br/><br/> Please contact to 24*7 Nursing Care Admin</h1><br/><br/><br/>		            	
		            @endif 
		            <a href="{{ env('APP_URL') }}">Back to Home page</a><br/><br/><br/><br/>
	            </div>    
			</div>
		</div>
	</div>
@endsection