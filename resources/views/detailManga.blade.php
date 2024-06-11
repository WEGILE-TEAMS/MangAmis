<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Manga</title>
</head>
<body>
    <div class="manga-item">
        <div class="manga-item">
            <h1>{{ $manga_title }}</h1>
            <h3>{{ $manga_author }}</h3>
            <img src="{{ $image }}" alt="" width="300" height="450">
            <p>{{ $manga_desc }}</p>
            <!-- Ini genre-genrenya -->
            @foreach ($genres as $genre)
                <h4>{{ $genre }}</h4>
            @endforeach
        </div>
    </div>
    <div class="chapter">
        <h2>List Chapter</h2>
        @if (!empty($chapters))
                <ul>
                    @foreach ($chapters as $chapter)
                        <li>
                            <!-- Volume berapa, chapter berapa, sama titlenya -->
                            Volume {{ $chapter['attributes']['volume'] ?? 'N/A' }} - Chapter {{ $chapter['attributes']['chapter'] ?? 'N/A' }}: {{ $chapter['attributes']['title'] ?? 'No Title' }}
                            <!-- Publishnya kapan -->
                            {{ isset($chapter['attributes']['publishAt']) ? (new DateTime($chapter['attributes']['publishAt']))->format('d F, Y') : 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No chapters available.</p>
            @endif
    </div>
    <!-- ini untuk similarManga -->
    <div class="similarManga">
        <h2>Similar Manga</h2>
        @foreach ($similar as $manga)
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
    </div>
</body>
</html>