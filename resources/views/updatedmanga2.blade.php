@extends('template/master')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Style/css/main.css') }}">
    {{-- @include('template/navbar') --}}
@endsection

@section('title')
    Home Page
@endsection


@section('content')
<section id="home">
    @include('template/navbar')
    <div id="top-manga">
        <div class="bg-line d-flex justify-content-center align-items-center">
            <div class="bg"></div>
        </div>
        <div class="container content">
            <div class="row">
                <div class="col-md-6 left-content d-flex flex-column justify-content-center align-items-start">
                    <h4>Top Manga</h4>
                    <div class="title d-flex justify-content-center align-items-center">
                        <h3>Gachiakuta</h3>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo sed laborum, officia cumque vel nisi autem voluptatem ut similique odit cum consequatur repellat nemo ea pariatur doloremque totam hic non.
                    </p>
                    <button class="btn btn-secondary">
                        Read Now
                    </button>
                </div>
                <div class="col-md-6 right-content d-flex justify-content-center align-items-center">
                    <div class="img-manga"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
