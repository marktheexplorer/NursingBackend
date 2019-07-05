@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Patient Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-pencil-alt"></i> Edit Patient</a>
                            </li>
                        </ul>
                        <form action="{{ route('patients.update', ['id' => $user->id]) }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                        @csrf
                        @method('put')
                            <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-8" id="tab-2">
                                    <div class="row">
                                       <div class="col-sm-4 form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Title" value="{{ old('name', $user->name) }}"/>
                                            @if ($errors->has('name'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email', $user->email) }}"/>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="DOB" value="{{ old('dob', $user->patient->dob) }}"/>
                                            @if ($errors->has('dob'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('dob') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender">
                                               <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                               <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                               <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Expected cost</label>
                                            <input type="text" class="form-control {{ $errors->has('range') ? ' is-invalid' : '' }}" name="range" placeholder="Range" value="{{ old('range', $user->patient->range) }}"/>
                                            @if ($errors->has('range'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('range') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="City" value="{{ old('city', $user->city) }}"/>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div> 
                                        <div class="col-sm-4 form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" placeholder="State" value="{{ old('state', $user->state) }}"/>
                                            @if ($errors->has('state'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Country</label>
                                            <input type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" placeholder="Country" value="{{ old('country', $user->country) }}"/>
                                            @if ($errors->has('country'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('country') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Diagnose</label>
                                            <input type="text" class="form-control {{ $errors->has('diagnose') ? ' is-invalid' : '' }}" name="diagnose" placeholder="Diagnose" value="{{ old('diagnose', $user->diagnose) }}"/>
                                            @if ($errors->has('diagnose'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('diagnose') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Availability</label>
                                            <input type="text" class="form-control {{ $errors->has('availability') ? ' is-invalid' : '' }}" name="availability" placeholder="Availability" value="{{ old('country', $user->patient->availability) }}"/>
                                            @if ($errors->has('availability'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('availability') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>  Select Image:</label>
                                    <div class="col-sm-12 form-group">
                                        <input type="file" name="service_image" class="form-control" onchange="readURL(this);">
                                        <img  id="preview" src="{{ asset(config('image.user_image_url').$user->service_image) }}" alt="your image">
                                        @if ($errors->has('service_image'))
                                            <span class="text-danger">
                                                <strong>{{ $errors->first('service_image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer-scripts')
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection