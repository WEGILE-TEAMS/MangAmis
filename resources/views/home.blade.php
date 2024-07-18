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
            // dd($genresString);
        @endphp
        <h2>{{ $manga["title"] }}</h2>
        <h5>{{ $manga["author_name"] }}</h5>
        <a href="{{ route('detailManga', [
            'id' => $manga['id'],
            'title' => $manga['title'],
            'author' => $manga['author_name'],
            'desc' => $manga['desc'],
            'genres' => $genresString,
            'cover_url' => $manga['cover_url']
        ]) }}">
            <img src="{{ $manga['image'] }}" alt="" width="300" height="450">
        </a>
        <p>{{ $manga["desc"] }}</p>
    @endforeach


    <title>Document</title>
</head>
<body>
    <h1>Top Manga</h1>
    <div class="TopManga">
        <h1>Judul =  {{ $temp[0]['title'] }}</h1>
        <p>Description = {{ $temp[0]['desc'] }}</p>
        <img src="{{ $temp[0]['image'] }}" alt="TopMangaImage">
    </div>
</body>
</html>
