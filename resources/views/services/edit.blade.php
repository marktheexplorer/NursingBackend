@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Service</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-pencil-alt"></i> Edit Service</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-2">
                                <form action="{{ route('services.update', ['id' => $service->id]) }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                                @csrf
                                @method('put')
                                    <div class="row">
                                       <div class="col-sm-12 form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" placeholder="Title" value="{{ old('name', $service->title) }}""/>
                                            @if ($errors->has('title'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label>Select Image:</label>
                                            <input type="file" name="service_image" class="" onchange="readURL(this);"><br/><?php
                                            if(empty($service->service_image)) { ?>
                                                <img id="preview" alt="your image" style="display: none;max-width:150px;max-height:150px;" /><?php
                                            }else{ ?>
                                                <img  id="preview" src="{{ asset(config('image.service_image_url').$service->service_image) }}" alt="your image" style="max-width:150px;max-height:150px;"><?php
                                            }?>

                                            @if ($errors->has('service_image'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('service_image') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <button class="btn btn-default" type="submit">Submit</button>
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
                    $('#preview').attr('src', e.target.result);
                    $('#preview').css('display', 'inline');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection