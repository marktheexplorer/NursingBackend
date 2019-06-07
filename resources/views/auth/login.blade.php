@extends('layouts.app')

@section('content')
<div class="content">
    <div class="brand">
        <a class="link" onclick="return void(0);">{{$_ENV['APP_NAME']}} Admin</a>
    </div>
    @include('flash::message')
    <form id="login-form" class="form-vertical" method="POST" action="{{ route('login') }}">
    @csrf
        <h2 class="login-title">Log in</h2>
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-envelope"></i></div>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group d-flex justify-content-between">
            
             @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" id="to-recover">Forget password?</a>
            @endif
        </div>
        <div class="form-group">
            <button class="btn btn-info btn-block" type="submit">Login</button>
        </div>
    </form>
</div>
@endsection
