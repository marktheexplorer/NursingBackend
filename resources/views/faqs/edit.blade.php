@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit FAQ</h1>
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
                        <div class="tab-content">
                            <form action="{{ route('faqs.update', ['id' => $faq->id]) }}" method="post" class="form-horizontal">
                            @csrf
                            @method('put')
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Question</label>
                                        <input type="text" class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" placeholder="Enter Question" value="{{ old('question', $faq->question) }}" required/>
                                        @if ($errors->has('question'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('question') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Answer</label>
                                        <textarea class="form-control" name="answer" rows="15">{{ old('answer', $faq->answer) }}</textarea>
                                        @if ($errors->has('answer'))
                                            <span class="invalid-feedback" role="alert" style="display:block">
                                                <strong>{{ $errors->first('answer') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Select Role</label>
                                        <select class="form-control" id="role" name="role_id">
                                            <option value="3" {{ old('role_id', $faq->role_id) == 3 ? 'selected' : '' }} >Client</option>
                                            <option value="2" {{ old('role_id', $faq->role_id) == 2 ? 'selected' : '' }}>Caregiver</option>
                                        </select>
                                        @if ($errors->has('role_id'))
                                            <span class="invalid-feedback" role="alert" style="display:block">
                                                <strong>{{ $errors->first('role_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>    
                                <div class="form-group col-md-12" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <button class="btn btn-info" type="submit">Submit</button>
                                        <input type="reset" value="Cancel" class="btn btn-danger" onclick="window.location.reload()">
                                    </div>
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
