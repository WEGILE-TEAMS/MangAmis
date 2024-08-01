@extends('template.master')

@section('title', 'Home Page')

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
                        <h3>{{ $topManga['title'] }}</h3>
                    </div>
                    <p>
                        @php
                            $topGenresString = implode(',', $topManga['genre']);
                            $topDesc = explode(" ", $topManga['desc']);
                            $topDesc = implode(" ", array_slice($topDesc, 0, 20));
                        @endphp

                        {{ $topDesc }}
                    </p>
                    <div class="container-button">
                        <a href="{{ route('detailManga', [
                            'id' => $topManga['id'],
                            'title' => $topManga['title'],
                            'author' => $topManga['author_name'],
                            'desc' => $topManga['desc'],
                            'genres' => $topGenresString,
                            'cover_url' => $topManga['cover_url']
                        ]) }}" class="btn btn-secondary">
                            Read Now
                        </a>
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
                @if (!empty($history[0]))
                        <div class="details">
                            <p>by {{ $history[0]['author_name'] }}</p>
                            <h5>{{ $history[0]['title'] }}</h5>
                            <p>
                                @php
                                    $desc = explode(" ", $history[0]['desc']);
                                    if (count($desc) > 40) {
                                        $desc = implode(" ", array_slice($desc, 0, 40));
                                    } else {
                                        $desc = $history[0]['desc'];
                                    }
                                @endphp
                                {{ $desc }}...
                            </p>
                        </div>
                        <div class="cover-manga-active" style="background-image: url('{{ $history[0]['image'] }}')">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end col-md-8 group-btn">
                        @php
                            $genresString = implode(',', $history[0]['genre']);
                        @endphp
                        <div class="container-button">
                            <a href="{{ route('detailManga', [
                                'id' => $history[0]['id'],
                                'title' => $history[0]['title'],
                                'author' => $history[0]['author_name'],
                                'desc' => $history[0]['desc'],
                                'genres' => $genresString,
                                'cover_url' => $history[0]['cover_url']
                            ]) }}" class="btn btn-secondary">Details</a>
                        </div>
                        <div class="container-button">
                            <button class="btn btn-primary">Read</button>
                        </div>
                    </div>
                    <div class="row justify-content-end col-md-8 history-second">
                        @foreach ($history as $key => $item)
                            @if ($key >= 1) <!-- Mulai dari indeks kedua (0-based index) -->
                                @php
                                    $genresString = implode(',', $item['genre']);
                                @endphp
                                <a href="{{ route('detailManga', [
                                    'id' => $item['id'],
                                    'title' => $item['title'],
                                    'author' => $item['author_name'],
                                    'desc' => $item['desc'],
                                    'genres' => $genresString,
                                    'cover_url' => $item['cover_url']
                                ]) }}" class="col-md-3 cover-manga" style="background-image: url('{{ $item['image'] }}')">
                                </a>
                            @endif
                        @endforeach
                        <div class="indicator col-md-12 d-flex justify-content-end align-items-center">
                            <div class="d-flex">
                                @if ($history->onFirstPage())
                                <div class="container-button prev">
                                    <button class="btn btn-secondary" disabled>
                                        <img src="images/next-icon.png" alt="Previous">
                                    </button>
                                </div>
                                @else
                                <div class="container-button prev">
                                    <button class="btn btn-secondary" onclick="location.href='{{ $history->previousPageUrl().'#history' }}'">
                                        <img src="images/next-icon.png" alt="Previous">
                                    </button>
                                </div>
                                @endif
                                @if ($history->hasMorePages())
                                <div class="container-button next">
                                    <button class="btn btn-secondary" onclick="location.href='{{ $history->nextPageUrl().'#history' }}'">
                                        <img src="images/next-icon.png" alt="Next">
                                    </button>
                                </div>
                                @else
                                    <div class="container-button next">
                                        <button class="btn btn-secondary" disabled>
                                            <img src="images/next-icon.png" alt="Next">
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="lines"></div>
                            <h5>{{ $history->currentPage() }} / {{ $history->lastPage() }}</h5>
                        </div>
                    </div>
                @else
                    <span class="text-white">
                        No history yet
                    </span>
                @endif
        </div>
    </div>
    <div id="new-update">
        <div class="container">
            <h5>NEW UPDATED MANGA</h5>
            <div class="lines"></div>
            <div class="row d-flex justify-content-between align-items-center">
                @foreach ($temp as $manga)
                    @php
                        $genresString = implode(',', $manga['genre']);
                    @endphp
                    <a href="{{ route('detailManga', [
                        'id' => $manga['id'],
                        'title' => $manga['title'],
                        'author' => $manga['author_name'],
                        'desc' => $manga['desc'],
                        'genres' => $genresString,
                        'cover_url' => $manga['cover_url']
                    ]) }}" class="manga-card d-flex flex-column" style="text-decoration: none; color: black;">
                        <div class="img" style="background-image: url('{{ $manga['image'] }}');"></div>
                        <div class="title">
                            {{ $manga['title'] }}
                        </div>
                        <div class="chp-title">
                            Chapter {{ $manga['chapter_number'] }} : {{ $manga['chapter_title'] }}
                        </div>
                    </a>
                @endforeach
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
@include('template.footer')
@endsection
