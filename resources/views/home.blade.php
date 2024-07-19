@extends('template/master')

@section('title')
    Home Page
@endsection

@section('content')
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
@endsection
