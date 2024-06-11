<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
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
</body>
</html>