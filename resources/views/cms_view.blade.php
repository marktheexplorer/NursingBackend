@extends('layouts.default')

@section('content')
@if($cms)
	<div class="container">
	  	{!! $cms->content !!}
	</div>
@else
	<div class="jumbotron text-center">
	  	<h1>Page content not found.</h1>
	  	<hr>
	</div>
@endif

@endsection
