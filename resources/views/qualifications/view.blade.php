@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Discipline Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('qualifications.index')}}">Disciplines</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Discipline Details</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-1">
                                <p><h5>Discipline : {{ ucfirst($qualification->name) }} </h5></p>
                                <p>Created At : {{$qualification->created_at->format('Y-m-d')}}</p>
                                <p>Updated At : {{$qualification->updated_at->format('Y-m-d')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection