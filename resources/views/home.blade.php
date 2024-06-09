<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>
        Read Again
    </h1>
    <div class="manga-list">
        @foreach($temp as $manga)
            <div class="manga-item">
                <h2>{{ $manga['title'] }}</h2>
                <h3>{{ $manga['author_name'] }}</h3>
                <img style="width: 200px;height=auto;" src="{{ $manga['cover_src'] }}" alt="">
                <p>{{ $manga['desc'] }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>