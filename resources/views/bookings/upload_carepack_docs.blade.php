@extends('layouts.default')

@section('content')
	@if(isset($data['error']))
		<div class="jumbotron text-center pb-1">
			<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;"><br/><br/><br/>
		  	<h1>{{ $data['error'] }}</h1>
		  	<hr>
		  	<h3> <br/>
		  		<a href="{{ env('APP_URL') }}" style="padding: 10px;border: 1px solid;background-color: #64b1e7;color: #fff;border-radius: 5px;text-decoration: none;" title="click here">Back to home page</a>
		  	</h3>
		</div>
		<div class="container"></div>
	@elseif(isset($data['upload']))	
		<div class="jumbotron text-center pb-1">
			<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;"><br/><br/><br/>
		  	<h1>{{ $data['message'] }}</h1><hr>
		  	<h3> <br/>
		  		<a href="{{ env('APP_URL') }}" style="padding: 10px;border: 1px solid;background-color: #64b1e7;color: #fff;border-radius: 5px;text-decoration: none;" title="click here">Back to home page</a>
		  	</h3>
		</div>
		<div class="container"></div>
	@else
		<div class="jumbotron text-center">
			<img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;"><br/><br/>
		  	<h1>Welcome, please upload your documents</h1><hr>
		  	<form action="{{ env('APP_URL') }}upload_carepack_docs" enctype = 'multipart/form-data' method="post" class="form-horizontal">
           		@csrf
                <div class="row">
                    <div class="col-sm-2 form-group"></div>
                    <div class="col-sm-10 form-group">
                        <label style="float:left;">Upload Documents:</label>
                    </div>    
                    <div class="col-sm-2 form-group"></div>    
                    <div class="col-sm-10 form-group">
                    	<input type="hidden" name="token" value="{{ $data['token'] }}" >
                        <input type="file" name="care_pack" class="" style="float:left;" accept=".zip,.rar,.7zip" onchange="checkValidation(this);">
                    <div class="col-sm-2 form-group"></div> 
                    <div class="col-sm-10 form-group" style="float:left;padding-left:0px;">    
                        @if ($errors->has('care_pack'))
                            <strong style="float: left;">{{ $errors->first('care_pack') }}</strong>
                        @else
                            <span style="float:left;font-size: 10px;">Please upload only compress file like zip, rare, 7zip</span>
                        @endif
                    </div>
                    <div class="col-sm-10 form-group"></div>    
                    <div class="col-sm-8 form-group">
                    <div class="form-group">
                        <input type="submit" class="btn btn-default" type="submit" style="padding: 5px;border: 1px solid;background-color: #64b1e7;color: #fff;border-radius: 5px;text-decoration: none;float: left;" id="submit_btn" value="submit" disabled>
                    </div>
                </div>
            </form>
		</div>
	@endif
@endsection

@section('footer-scripts')
    <script>
        function checkValidation(e) {
            if (e.files.length > 0) {
		        $("#submit_btn").removeAttr("disabled");
		    }
		}
    </script>
@endsection