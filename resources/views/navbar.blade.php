@extends('master')

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/css/main.css') }}">
@endsection

@section('navbar')
<section id="navbar">
    <nav class="navbar navbar-expand-lg bg-transparent">
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
                    <li class="nav-item nav-line">
                        ____________________________________________________________________________________
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bolder icon-link link-opacity-50 icon-link-hover"
                            style="--bs-link-hover-color-rgb: 200, 0, 0; text-decoration: none; padding: 0 10px"
                            aria-current=" page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bolder icon-link link-opacity-50 icon-link-hover"
                            style="--bs-link-hover-color-rgb: 200, 0, 0; text-decoration: none; padding: 0 10px"
                            href="#">Manga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bolder icon-link link-opacity-50 icon-link-hover"
                            style="--bs-link-hover-color-rgb: 200, 0, 0; text-decoration: none; padding: 0 10px"
                            href="#">Random</a>
                    </li>
                    <li class="nav-item nav-line">
                        __________________________________________________________________
                </ul>
            </div>
            <form class="d-flex" role="search">
                <input class="form-control me-2 rounded-0" type="search" placeholder="Search Manga" aria-label="Search">
                <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
            </form>
        </div>
    </nav>
</section>
@endsection