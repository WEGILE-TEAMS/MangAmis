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
        <h1>{{ $manga_title }}</h1>
        <h3>{{ $manga_author }}</h3>
        <img src="{{ $image }}" alt="" width="300" height="450">
        <p>{{ $manga_desc }}</p>
        @foreach ($genres as $genre)
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
        @foreach ($similar as $manga)
            <h2>{{ $manga['title'] }}</h2>
            <h5>{{ $manga['author_name'] }}</h5>
            <a href="{{ route('detailManga', [
                'id' => $manga['id'],
                'title' => urlencode($manga['title']),
                'author' => urlencode($manga['author_name']),
                'desc' => urlencode($manga['desc']),
                'genres' => urlencode(implode(',', $manga['genre'])),
                'cover_id' => $manga['cover_id']
            ]) }}">
                <img src="{{ $manga['image'] }}" alt="" width="300" height="450">
            </a>
            <p>{{ $manga['desc'] }}</p>
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
