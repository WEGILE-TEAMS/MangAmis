@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
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