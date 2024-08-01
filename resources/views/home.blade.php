@extends('template/master')

@section('title')
    Home Page
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('Style/css/main.css') }}">
    @include('template/footer')
@endsection

@section('content')

<section id="navbar">
    <nav class="navbar navbar-expand-lg bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="images/logo-navbar.png" class="navbar_logo" alt="navbar-logo">
                <div class="lines"></div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link fw-bolder navbar-items"
                            aria-current=" page" href="#">Home</a>
                    </li>
                    <li class="nav-item" style="margin: 0 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="#">Manga</a>
                    </li>
                    <li class="nav-item" style="margin-right: 25px;">
                        <a class="nav-link fw-bolder navbar-items"
                            href="#">Random</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bolder navbar-items"
                            href="#">Community</a>
                    </li>
                </ul>
            </div>
            <form class="d-flex align-items-center search-box" role="search">
                <div class="lines"></div>
                <div class="profile d-flex justify-content-center align-items-center">
                    <div class="circle"></div>
                </div>
                <div class="container-button">
                    <input class="form-control me-2 rounded-0" type="search" placeholder="Search Manga..." aria-label="Search">
                    <div class="icon-search"></div>
                </div>
            </form>
        </div>
    </nav>
</section>
<section id="home">
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
                        {{ \Illuminate\Support\Str::limit($topManga['desc'], 100) }}
                    </p>
                    <div class="container-button">
                        <button class="btn btn-secondary">
                            Read Now
                        </button>
                    </div>
                </div>
                <div class="col-md-6 right-content d-flex justify-content-center align-items-center">
                    <div class="img-manga"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="history">
        <div class="container d-flex flex-column justify-content-center align-items-end pt-4">
            <h4 class="title">READ AGAIN</h4>
            <div class="lines"></div>
            <div class="col-md-8 history-content d-flex justify-content-end align-end-end">
                <div class="details">
                    <p>by Tatsuki Fujimoto</p>
                    <h5>CHAINSAW<br>MAN</h5>
                    <p>
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut dolor in reprehenderit in voluptate velit esse cillum dolore eu 
                    </p>
                </div>
                <div class="cover-manga-active">                 
                </div>
            </div>
            <div class="d-flex justify-content-end col-md-8 group-btn">
                <div class="container-button">
                    <button class="btn btn-secondary">Details</button>
                </div>
                <div class="container-button">
                    <button class="btn btn-primary">Read</button>
                </div>
            </div>
            <div class="row justify-content-end col-md-8 history-second">
                <div class="col-md-3 cover-manga"></div>
                <div class="col-md-3 cover-manga"></div>
                <div class="col-md-3 cover-manga"></div>
                <div class="indicator col-md-12 d-flex justify-content-end align-items-center">
                    <div class="d-flex">
                        <div class="container-button prev">
                            <button class="btn btn-secondary">
                                <img src="images/next-icon.png" alt="">
                            </button>
                        </div>
                        <div class="container-button next">
                            <button class="btn btn-secondary"> 
                                <img src="images/next-icon.png" alt="">
                            </button>
                        </div>
                    </div>
                    <div class="lines"></div>
                    <h5>1/2</h5>
                </div>
            </div>
        </div>
    </div>
    <div id="new-update">
        <div class="container">
            <h5>NEW UPDATED MANGA</h5>
            <div class="lines"></div>
            <div class="row d-flex justify-content-between align-items-center">
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/dandan-book.jpg');"></div>
                    <div class="title">
                        Dandandan
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/kaijuu-cover.jpg');"></div>
                    <div class="title">
                        Kaijuu No.8
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/86-books.jpg');"></div>
                    <div class="title">
                        86
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
                <div class="manga-card d-flex flex-column">
                    <div class="img" style="background-image: url('images/twaf.jpg');"></div>
                    <div class="title">
                        The World After The Fall
                    </div>
                    <div class="chp-title">
                        Chapter 110 : Beginning after the en...
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 90px;">
                <div class="text-center">        
                    <div class="container-button">
                        <button class="btn btn-secondary">See More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection






{{-- @section('content')
    @foreach($temp as $manga)
        @php
            $genresString = implode(',', $manga['genre']);
        @endphp
        <h2>{{ $manga["title"] }}</h2>
        <h5>{{ $manga["author_name"] }}</h5>
        <a href="{{ route('detailManga', [
            'id' => $manga['id'],
            'title' => urlencode($manga['title']),
            'author' => urlencode($manga['author_name']),
            'desc' => urlencode($manga['desc']),
            'genres' => urlencode($genresString),
            'cover_id' => $manga['cover_id']
        ]) }}">
            <img src="{{ $manga['image'] }}" alt="" width="300" height="450">
        </a>
        <p>{{ $manga["desc"] }}</p>
    @endforeach
    
    <h1>Top Manga</h1>
    <div class="TopManga">
        <h1>Title =  {{ $topManga['title'] }}</h1>
        <p>Description  = {{ $topManga['desc'] }}</p>
        <img src="{{ $topManga['image'] }}" alt="TopMangaImage" width="300" height="450">
    </div>
@endsection --}}
