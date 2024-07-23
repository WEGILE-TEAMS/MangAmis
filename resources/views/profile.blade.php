<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
</head>
<body>
    <h1>Bookmarks</h1>
    @foreach ($bookmarks as $bookmark)
        @php
            $genresString = implode(',', $bookmark['genre']);
        @endphp
        <a href="{{ route('detailManga', [
            'id' => $bookmark['id'],
            'title' => $bookmark['title'],
            'author' => $bookmark['author_name'],
            'desc' => $bookmark['desc'],
            'genres' => $genresString,
            'cover_url' => $bookmark['image']
        ]) }}" class="manga-card d-flex flex-column" style="text-decoration: none; color: black;">
            <div class="title">
                {{ $bookmark['title'] }}
            </div>
            <img src="{{ $bookmark['image'] }}" alt="" style="width: 300px; height: 400px">
        </a>
    @endforeach
</body>
</html>
