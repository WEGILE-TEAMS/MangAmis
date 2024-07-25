<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Manga</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="manga-item">
        <h1>{{ $temp['manga_title'] }}</h1>
        <h3>{{ $temp['manga_author'] }}</h3>
        <img src="{{ $temp['image'] }}" alt="" width="300" height="450">
        <p>{{ $temp['manga_desc'] }}</p>
        @foreach ($temp['genres'] as $genre)
            <h4>{{ $genre }}</h4>
        @endforeach
    </div>

    <div class="chapter">
        <h2>List Chapter</h2>
        @if (!empty($chapters))
            <ul>
                @foreach ($chapters as $chapter)
                    <li>
                        <a href="" class="manga-link"
                           data-manga-id="{{ $manga_id }}" data-chapter-id="{{ $chapter['id'] }}">
                            Volume {{ $chapter['attributes']['volume'] ?? 'N/A' }} - Chapter {{ $chapter['attributes']['chapter'] ?? 'N/A' }}: {{ $chapter['attributes']['title'] ?? 'No Title' }}
                            {{ isset($chapter['attributes']['publishAt']) ? (new DateTime($chapter['attributes']['publishAt']))->format('d F, Y') : 'N/A' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No chapters available.</p>
        @endif
    </div>

    <div class="similarManga">
        <h2>Similar Manga</h2>
        @foreach ($temp['similar'] as $manga)
            <h2>{{ $manga['title'] }}</h2>
            <h5>{{ $manga['author_name'] }}</h5>
            <a href="{{ route('detailManga', [
                'id' => $manga['id'],
                'title' => urlencode($manga['title']),
                'author' => urlencode($manga['author_name']),
                'desc' => urlencode($manga['desc']),
                'genres' => urlencode(implode(',', $manga['genre'])),
                'cover_url' => urlencode($manga['cover_url'])
            ]) }}">
                <img src="{{ $manga['image'] }}" alt="" width="300" height="450">
            </a>
            <p>Chapter {{ $manga['chapter_title'] }} : {{ $manga['chapter_number'] }}</p>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.manga-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                let mangaId = e.target.getAttribute('data-manga-id');
                let chapterId = e.target.getAttribute('data-chapter-id');
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                }).then(response => {
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        window.location.href = e.target.href;
                    } else {
                        alert('Failed to save manga history.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });

    
    </script>
</body>
</html>
