@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Contact Us Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}" >Contact Us</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link @if(count($errors) == 0) active @endif " href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Details</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade @if(count($errors) > 0) '' @else show active @endif" id="tab-1">
                                <ul class="media-list media-list-divider m-0">
                                	<li class="media">
                                        <div class="media-img"><i class="far fa-user"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $contact->user->f_name.''.$contact->user->m_name.''.$contact->user->l_name }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-envelope"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $contact->user->email }} </div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="fas fa-phone-volume"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">{{ $contact->user->mobile_number }}</div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="far fa-comment-alt"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading">{{ $contact->message }} </div>
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
