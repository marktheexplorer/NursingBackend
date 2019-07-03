@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Qualification Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('qualification.index')}}">Qualifications</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Qualification Details</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-1">
                            <p><h5>{{ ucfirst($qualification->title) }} </h5></p>
                            <p>{!! $qualification->description !!} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection