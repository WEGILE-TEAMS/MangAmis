@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')

<section id="read-manga">
    <div class="bg-dark-group">
        <div class="bg-dark-item"></div>
        <div class="bg-dark-item"></div>
        <div class="bg-dark-item"></div>
        <div class="bg-dark-item"></div>
        <div class="bg-dark-item"></div>
        <div class="bg-dark-item"></div>
    </div>
    <div class="container">
        <h5 class="slug">Manga > Detail<span> > Read</span></h5>
        <div class="top-content">
            <div class="manga-detail">
                <h5 class="chapter-number">
                    Chapter <span>{{ $chapterDetails['number'] }}</span>
                </h5>
                <div class="d-flex justify-content-between">
                    <h5 class="chapter-title">
                        {{ $chapterDetails['title'] }}
                    </h5>
                    <div class="btn-group d-flex justify-content-end">
                        @if ($prev)
                            <div class="container-button">
                                <a href="{{ route('read.manga', [
                                    "mangaTitle" => request()->segment(2),
                                    "chapterId" => $prev
                                ]) }}" class="btn btn-secondary">
                                    Prev Chapter
                                </a>
                            </div>
                        @endif
                        @if ($next)
                            <div class="container-button">
                                <a href="{{ route('read.manga', [
                                    "mangaTitle" => request()->segment(2),
                                    "chapterId" => $next
                                ]) }}" class="btn btn-primary">
                                Next Chapter
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
                
        </div>

        <div class="manga-panel">
            @foreach ($images as $img)
                <img src="{{ $img }}" alt="">
            @endforeach
        </div>
    </div>
    <div class="manga-bottom">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="btn-group">
                    @if ($prev)
                        <div class="container-button">
                            <a href="{{ route('read.manga', [
                                "mangaTitle" => request()->segment(2),
                                "chapterId" => $prev
                            ]) }}" class="btn btn-primary">
                                Prev Chapter
                            </a>
                        </div>
                    @endif
                    <div class="container-button mx-3">
                        <a href="{{ route('detailManga', [
                            'id' => $detailManga['id'],
                            'title' => $detailManga['title'],
                            'author' => $detailManga['author_name'],
                            'desc' => $detailManga['desc'],
                            'genres' => implode(',', $detailManga['genre']),
                            'cover_url' => $detailManga['cover_url']
                        ]) }}" class="btn btn-secondary">
                            Chapter List
                        </a>
                    </div>
                    @if ($next)
                        <div class="container-button">
                            <a href="{{ route('read.manga', [
                                "mangaTitle" => request()->segment(2),
                                "chapterId" => $next
                            ]) }}" class="btn btn-primary">
                            Next Chapter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="manga-detail d-flex">
                <img src="{{ $detailManga['image'] }}" class="manga-cover">
                <div class="details d-flex flex-column justify-content-between">
                    <div class="">
                        <div class="author">by {{ $detailManga['author_name'] }}</div>
                        <h5 class="manga-title">
                            {{-- CHAINSAW <span>MAN</span> --}}
                            {{ $detailManga['title'] }}
                        </h5>
                        <p class="synopsis">
                            @php
                            $desc = explode(" ", $detailManga['desc']);
                            if (count($desc) > 50) {
                                $desc = implode(" ", array_slice($desc, 0, 50));
                            } else {
                                $desc = $detailManga['manga_desc'];
                            }
                        @endphp
                        {{ $desc }}...
                        </p>    
                    </div>
                    <div class="chapter-groups">
                        @foreach (array_slice($chapters, 0, 4) as $chp)
                            <div class="container-button chapter-item">
                                <a href="{{ route('read.manga', [
                                    "mangaTitle" => request()->segment(2),
                                    "chapterId" => $chp['id']
                                ]) }}" class="btn btn-primary {{ ( request()->segment(3) == $chp['id']) ? 'active' : '' }}">
                                    Chapter {{ $chp['attributes']['chapter'] }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('template.footer')
@endsection