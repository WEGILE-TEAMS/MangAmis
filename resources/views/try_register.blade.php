@extends('master')

@section('title')
MangAmis - Register
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/login.css') }}">
@endsection

@section('header')
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/MangaMis.png" class="navbar_logo" alt="navbar-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Manga</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Random</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endsection

@section('content')
<!-- <div style="background-image: url('{{ asset('images/background/login_register.jpg') }}')"></div> -->
@endsection

@section('footer')

@endsection