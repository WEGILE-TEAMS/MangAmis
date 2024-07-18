@extends('template.master')

@section('title', 'Home Page')

@section('content')

<section class="base-detail">
    <div id="detail">
        <h5 class="slug container">Manga<span> > Detail</span></h5>
        <div class="top-content">
            <div class="container d-flex">
                <img src="{{ $temp['image'] }}" class="manga-cover" alt="">
                <div class="manga-detail d-flex flex-column">
                    <h6 class="author">by {{ $temp['manga_author'] }}</h6>
                    <h4 class="title">{{ $temp['manga_title'] }}</h4>
                    <p class="synopsis">
                        {{ $temp['manga_desc'] }}
                    </p>
                    <h5 class="genres">
                        @foreach ($temp['genres'] as $key => $genre)
                            @if ($key % 2 == 0)
                                {{ $genre }} / 
                            @else
                                <span>{{ $genre }}</span>
                            @endif
                        @endforeach
                    </h5>
                </div>
                <div class="btn-group d-flex flex-column justify-content-end align-items-center">
                    <div class="container-button mb-3">
                        <button class="btn btn-secondary">
                            Save Manga
                        </button>
                    </div>
                    <div class="container-button">
                        <a href="/read-manga.html" class="btn btn-primary">
                            Read First Chapter
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter-list container">
            <h5>List of Chapter</h5>

            @if (!empty($temp['chapters']))
                @foreach ($temp['chapters'] as $chapter)
                <a href="" class="chapter-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="chapter-cover"></div>
                        <h6 class="chapter-title">Chapter {{ $chapter['attributes']['chapter'] }} : {{ $chapter['attributes']['title'] }}</h6>
                    </div>
                    <span>May, 14 2024</span>
                    <div class="chapter-views d-flex justify-content-center">
                        <div class="eye-icon"></div>
                        <span class="views-text">6k</span>
                    </div>
                    <span class="chapter-number" >#104</span>
                </a>
                @endforeach
                <div class="row my-5">
                    <div class="text-center">        
                        <div class="container-button">
                            <button class="btn btn-secondary">Load More</button>
                        </div>
                    </div>
                </div>
            @else
                <p>No chapters available.</p>
            @endif
            


            
        </div>

        <div id="simillar-manga">
            <div class="container">
                <h5>SIMILLAR MANGA</h5>
                <div class="lines"></div>
                <div class="row d-flex justify-content-between align-items-center">
                    @foreach ($temp['similar'] as $manga)
                    <a href="{{
                        route('detailManga', [
                            'id' => $manga['id'],
                            'title' => $manga['title'],
                            'author' => $manga['author_name'],
                            'desc' => $manga['desc'],
                            'genres' => implode(',', $manga['genre']),
                            'cover_url' => $manga['cover_url']
                        ])
                    }}" 
                       class="manga-card d-flex flex-column">
                        <div class="img" style="background-image: url('{{ $manga['image'] }}');"></div>
                        <div class="title">
                            {{ $manga['title'] }}
                        </div>
                        <div class="chp-title">
                            Chapter 110 : Beginning after the en...
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection