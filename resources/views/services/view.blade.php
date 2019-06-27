@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">User Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Users</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="ibox">
                    <div class="ibox-body text-center">
                        <div class="m-t-20">
                            <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" />
                        </div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ ucfirst($user->name) }}</h5>
                        <div class="m-b-20 text-muted">User</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> User Details</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-phone-volume"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->mobile_number }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-envelope"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $user->email }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $user->location }} </div>
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