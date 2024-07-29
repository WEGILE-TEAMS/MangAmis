@extends('template/master')

@section('title')
Login Page
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/css/main.css') }}" />
@endsection

@section('content')
@if(session()->has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session()->has('loginError'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('loginError') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<section id="login">
    <div class="container">
        <h2>SIGN <span>IN</span></h2>
        <form action="/login" method="post">
            @csrf
            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" class="form-control @error('user_email') is-invalid @enderror" autofocus id="user_email" name="user_email" value="{{ old('user_email') }}" placeholder="Your email address" />
                @error('user_email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Your password" required />
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="container-button" style="width: 100% !important;">
                    <button class="btn btn-primary" type="submit">SIGN IN</button>
                </div>
                <div class="container-button" style="width: 100% !important;">  
                    <a href="/register" class="btn btn-secondary">SIGN UP</a>
                </div>
            </div>
        </form>
    </div>

    <div class="logo d-flex align-items-center justify-content-center">
        <img src="/images/MangaMis.png" alt="Logo" />
    </div>
</section>
@endsection
