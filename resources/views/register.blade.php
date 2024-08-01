@extends('template/master')

@section('title', 'Sign Up Page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Style/css/register.css') }}" />
    <link rel="stylesheet" href="{{ asset('Style/css/main.css') }}" />
@endsection

@section('content')
<section id="login">
    <div class="container">
        <h2>SIGN <span>UP</span></h2>
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            <form action="/register" method="post">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required placeholder="Your username" />
                    @error('username')

                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" class="form-control @error('user_email') is-invalid @enderror" id="user_email" name="user_email" value="{{ old('user_email') }}" required placeholder="Your email address" />
                @error('user_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="user_password">Password</label>
                <input type="password" class="form-control @error('user_password') is-invalid @enderror" id="user_password" name="user_password" required placeholder="Your password" />
                @error('user_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
              <div class="container-button" style="width: 100% !important;">
                <button class="btn btn-primary" type="submit">SIGN UP</button>
              </div>  
              <div class="container-button" style="width: 100% !important;">
                <a href="/login" class="btn btn-secondary">SIGN IN</a>
              </div>  
            </div
        </form>
    </div>

    <div class="logo d-flex align-items-center justify-content-center">
        <img src="/images/MangaMis.png" alt="Logo" />
    </div>
</section>
@endsection
