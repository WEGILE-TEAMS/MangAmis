<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
