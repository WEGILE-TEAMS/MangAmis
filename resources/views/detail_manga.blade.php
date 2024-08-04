@extends('template.master')

@section('title', 'Home Page')

@section('content')
@push('styles')
    <style>
        .chapter-item {
            display: flex !important;
        }
        .chapter-item-top {
            display: none !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush

@include('template.navbar')
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
                        @php
                            $desc = explode(" ", $temp['manga_desc']);
                            if (count($desc) > 50) {
                                $desc = implode(" ", array_slice($desc, 0, 50));
                            } else {
                                $desc = $temp['manga_desc'];
                            }
                        @endphp
                        {{ $desc }}...
                    </p>
                    <h5 class="genres">
                        @foreach ($temp['genres'] as $key => $genre)
                            @if ($key % 2 == 0)
                                <span>{{ $genre }}</span>
                            @else
                                {{ $genre }}
                            @endif
                            @if ($key < count($temp['genres']) - 1)
                                /
                            @endif
                            @if (($key + 1) % 6 == 0 && $key < count($temp['genres']) - 1)
                                <br> <br>
                            @endif
                        @endforeach

                    </h5>
                </div>
                <div class="btn-group d-flex flex-column justify-content-end align-items-center">
                    <div class="container-button mb-3">
                        <button class="bookmark-link btn btn-secondary"
                        data-manga-id="{{ $temp["manga_id"] }}" data-user-id="{{ Auth()->user()->id }}">
                            Save Manga
                        </button>
                    </div>
                    <div class="container-button">
                        @php
                            $totalChapters = count($temp['chapters']);

                            if($totalChapters > 0) {
                                $lastIndex = count($temp['chapters']) - 1;
                            }
                        @endphp
                        @if ($totalChapters > 0)
                        <a href="{{ route('read.manga', [
                            "mangaTitle" => $temp['title'],
                            "chapterId" => $temp['chapters'][$lastIndex]['id']
                        ]) }}" class="btn btn-primary">
                            Read First Chapter
                        </a>

                        @else
                        <a href="#" class="btn btn-primary disabled">
                            Read First Chapter
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter-list container">
            <h5>List of Chapter</h5>

            @if (!empty($temp['chapters']))
                @foreach ($temp['chapters'] as $index => $chapter)
                <a
                href="{{ route('read.manga', [
                    "mangaTitle" => $temp['title'],
                    "chapterId" => $chapter['id']
                ]) }}"
                data-manga-id = "{{ $temp['manga_id'] }}" data-chapter-id="{{ $chapter['id'] }}"
                class="manga-link chapter-item d-flex justify-content-between align-items-center {{ $index > 4 ? 'chapter-item-top' : '' }}">
                    <div class="d-flex align-items-center">
                        {{-- <div class="chapter-cover"></div> --}}
                        <h6 class="chapter-title">Chapter {{ $chapter['attributes']['chapter'] }} : {{ $chapter['attributes']['title'] }}</h6>
                    </div>
                    <span>May, 14 2024</span>
                    <div class="chapter-views d-flex justify-content-center">
                        <div class="eye-icon"></div>
                        <span class="views-text">6k</span>
                    </div>
                    <span class="chapter-number">#{{ $chapter['attributes']['chapter'] }}</span>
                </a>
                @endforeach
            @else
            <a
            href="#"
            class="manga-link chapter-item d-flex justify-content-center align-items-center">
                <h5 class="title">No Chapters Available</h5>
            </a>
            @endif

            @if (count($temp['chapters']) > 4)
                <div class="row my-5">
                    <div class="text-center">
                        <div class="container-button">
                            <button id="show-all" class="btn btn-secondary">Show All</button>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div id="simillar-manga">
            <div class="container">
                <h5>SIMILAR MANGA</h5>
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
                            Chapter {{ $manga['chapter_number'] }} : {{ $manga['chapter_title'] }}
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@include('template.footer')

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var showAllButton = document.getElementById('show-all');
        if (showAllButton) {
            console.log('Show All button found');
            showAllButton.addEventListener('click', function(event) {
                event.preventDefault();
                var chapters = document.querySelectorAll('.chapter-item');
                chapters.forEach(function(chapter) {
                    chapter.style.display = 'flex';
                    chapter.style.removeProperty('display');
                    chapter.classList.remove('chapter-item-top');
                });
            });
            showAllButton.style.display = 'none';
        } else {
            console.log('Show All button not found');
        }

        // Update History
        document.querySelectorAll('.manga-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                let mangaId = e.currentTarget.getAttribute('data-manga-id');
                let chapterId = e.currentTarget.getAttribute('data-chapter-id');
                let csrfToken = "{{ csrf_token() }}";
                let href = e.currentTarget.getAttribute('href');

                if (!mangaId || !chapterId) {
                    console.error('Manga ID or Chapter ID is missing.');
                    alert('Manga ID or Chapter ID is missing.');
                    return;
                }

                fetch('/save-manga-history', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        manga_id: mangaId,
                        chapter_id: chapterId,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok: ${response.statusText}`);
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (error) {
                            console.error('Response is not valid JSON:', text);
                            throw new Error(`Invalid JSON: ${error.message}`);
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = href;
                    } else {
                        alert('Failed to save manga history.');
                        console.error('Server responded with an error:', data.message, data.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving manga history.');
                });
            });
        });



    });

    var bookmarkButton = document.querySelector('.bookmark-link');
    if (bookmarkButton) {
        bookmarkButton.addEventListener('click', function(event) {
            event.preventDefault();
            var mangaId = this.getAttribute('data-manga-id');
            var userId = this.getAttribute('data-user-id');

            fetch('/save-bookmark', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    manga_id: mangaId,
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                } else {
                    alert('Failed to bookmark manga');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
</script>
@endpush
@endsection
