<!DOCTYPE html>
<html>
<head>
    <title>Updated Manga</title>
</head>
<body>
    <h1>Updated Manga</h1>
    <ul>
        @foreach($manga as $m)
            <li>
                <h2>{{ $m['attributes']['title']['en'] ?? 'No title' }}</h2>
                <p>Updated at: {{ $m['attributes']['updatedAt'] }}</p>
                @if(isset($m['latestChapter']))
                    <h3>Latest Chapter: {{ $m['latestChapter']['attributes']['title'] ?? 'No title' }}</h3>
                    <p>Chapter number: {{ $m['latestChapter']['attributes']['chapter'] }}</p>
                    <p>Published at: {{ $m['latestChapter']['attributes']['publishAt'] }}</p>
                @else
                    <p>No latest chapter available</p>
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>
