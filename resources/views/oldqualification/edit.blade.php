@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Qualification</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('qualification.index') }}">Qualifications</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-pencil-alt"></i> Edit Qualification</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-2">
                                <form action="{{ route('qualification.update', ['id' => $qualification->id]) }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                                @csrf
                                @method('put')
                                    <div class="row">
                                       <div class="col-sm-12 form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" placeholder="Title" value="{{ old('name', $qualification->title) }}""/>
                                            @if ($errors->has('title'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label>Description </label>
                                            <textarea class="form-control" name="description" rows="15">{{ old('name', $qualification->description) }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="text-danger" >
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div> 
                                        <div class="form-group">
                                            <button class="btn btn-default" type="submit">Submit</button>
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