@extends('layouts.default')

@section('content')
	@if(isset($data['error']))
		<div class="jumbotron text-center pb-1">
			<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;"><br/><br/><br/>
		  	<h1>{{ $data['error'] }}</h1>
		  	<hr>
		  	<h3> <br/><a href="{{ env('APP_URL') }}" style="padding: 10px;border: 1px solid;background-color: #64b1e7;color: #fff;border-radius: 5px;text-decoration: none;" title="click here">Click Here</a></h3>
		</div>
		<div class="container">
		</div>
	@else
		<div class="jumbotron text-center">
			<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;"><br/><br/>
		  	<h1>Welcome, please upload your documents</h1>
		  	<hr>
		</div>
	@endif
@endsection
