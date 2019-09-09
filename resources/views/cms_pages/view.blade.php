@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-heading">
        <h1 class="page-title">CMS Page Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('cms.index') }}" >CMS Pages</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox ibox-success">
                    <div class="ibox-head">
                        <div class="ibox-title">{{ $cms->title }}</div>
                    </div>
                    <div class="ibox-body">{!! $cms->content !!} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
