@extends('layouts.app')

@section('content')
<div class="content">
    <div class="brand">
        <a class="link" onclick="return void(0);">{{$_ENV['APP_NAME']}} Admin</a>
    </div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif 
    <form id="forgot-form"  method="POST" action="{{ route('password.email') }}">
    @csrf
        <h3 class="m-t-10 m-b-10">Forgot password</h3>
        <p class="m-b-20">Enter your email address below and we'll send you password reset link.</p>
        <div class="form-group">
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button class="btn btn-info btn-block" type="submit">{{ __('Send Password Reset Link') }}</button>
        </div>
    </form>
</div>
@endsection
