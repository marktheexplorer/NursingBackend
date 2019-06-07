@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Admin Profile</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="ibox">
                    <div class="ibox-body text-center">
                        <div class="m-t-20">
                            <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" />
                        </div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ ucfirst(Auth::user()->name) }}</h5>
                        <div class="m-b-20 text-muted">Administrator</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link @if(count($errors) == 0) active @endif " href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(count($errors) > 0) active @endif" href="#tab-2" data-toggle="tab"><i class="ti-settings"></i> Edit Profile</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade @if(count($errors) > 0) '' @else show active @endif" id="tab-1">
                                <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Details</h5>
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-phone-volume"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ Auth::user()->mobile_number }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-envelope"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ Auth::user()->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ Auth::user()->location }} </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane fade @if(count($errors) > 0) show active @endif" id="tab-2">
                                <form action="{{ route('update.profile', ['id' => Auth::id()]) }}" method="post" class="form-horizontal">
                                @csrf
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Name" value=" {{ old('name', Auth::user()->name) }}" required/>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6  form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value=" {{ Auth::user()->email }}" readonly="readonly" />
                                             <span class="help-block">Email address cant be change</span>
                                        </div>
                                        <div class="col-sm-6  form-group">
                                            <label>Mobile Number</label>
                                            <input type="number" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number', Auth::user()->mobile_number)}}" required>
                                            @if ($errors->has('mobile_number'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('mobile_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address / Location</label>
                                        <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="address" placeholder="Location" value="{{ old('location', Auth::user()->location) }}" />
                                        @if ($errors->has('location'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('location') }}</strong>
                                            </span>
                                        @endif
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
    </div>
</div>
@endsection
