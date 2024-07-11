@extends('template/master')

@section('title')
Login Page
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('Style/css/main.css')}}" />
@endsection

@section('content')
@if(session()->has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success')}}
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
                <label for="">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="user_email" autofocus
                    id="email" required value="{{old('email')}}" placeholder="Your email address" />
            </div>
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="form-group">
                <label for="">Password</label>
                <div>
                    <input type="password" class="form-control" id="password" placeholder="Your password" required />
                    <!-- <div class="eye"></div> -->
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <button class="btn btn-primary" type="submit">SIGN IN</button>
                <a href="/register" class="btn btn-secondary">SIGN UP</a>
                <!-- <button class="btn btn-secondary">SIGN UP</button> -->
            </div>
        </form>
    </div>

    <div class="logo d-flex aling-items-center justify-content-center">
        <img src="/images/MangaMis.png" alt="" />
    </div>
</section>

@endsection