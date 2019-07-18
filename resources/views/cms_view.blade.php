@extends('layouts.default')

@section('content')
@if($cms)
	<div class="jumbotron text-center pb-1">
		<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;">
	  	<h1>{{ $cms->title }}</h1>
	  	<hr>
	</div>
	<div class="container">
	  	{!! $cms->content !!}
	</div>
@else
	<div class="jumbotron text-center">
		<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;">
	  	<h1>Page content not found.</h1>
	  	<hr>
	</div>
@endif

@endsection
