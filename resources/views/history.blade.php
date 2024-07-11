<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($histories as $history)
        <h1>{{ $history["title"] }}</h1>
        @php
            $genresString = implode(',', $history['genre']);
            // dd($genresString);
        @endphp
        <a href="{{ route('detailManga', [
            'id' => $history['id'],
            'title' => urlencode($history['title']),
            'author' => urlencode($history['author_name']),
            'desc' => urlencode($history['desc']),
            'genres' => urlencode($genresString),
            'cover_id' => $history['cover_id']
        ]) }}">
            <img src="{{ $history['image'] }}" alt="" width="300" height="450">
        </a>
    @endforeach
</body>
</html>
