@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Add FAQ</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('faqs.index') }}">FAQs</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Add FAQ</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <form action="{{ route('faqs.store') }}" method="post" class="form-horizontal">
                            @csrf
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Question</label>
                                        <input type="text" class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" placeholder="Enter Question" value="{{ old('question') }}" required/>
                                        @if ($errors->has('question'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('question') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Answer</label>
                                        <textarea class="form-control" name="answer" rows="15">{{ old('answer') }}</textarea>
                                        @if ($errors->has('answer'))
                                            <span class="invalid-feedback" role="alert" style="display:block">
                                                <strong>{{ $errors->first('answer') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>    
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <div class="form-group"><button class="btn btn-info" type="submit">Submit</button></div>
                                </div>
                            </form>
                        </div>
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
        tinymce.init({
            selector: 'textarea',
            menu: {
                view: {title: 'Enter Code', items: 'code'}
            },
            plugins: 'code, textpattern, textcolor',
            toolbar: [
                'undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright alignjustify | fontselect | forecolor | backcolor'
            ],
            theme_advanced_fonts: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n',
        });
    </script>
@endsection
