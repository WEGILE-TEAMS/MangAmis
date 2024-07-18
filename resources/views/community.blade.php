<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Community</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .room {
            background-color: gray;
        }
    </style>
</head>
<body>
    {{-- buat search bar --}}
    <form action="{{ route('community') }}" method="GET">
        <input type="text" name="query" placeholder="Enter manga title" value="{{ old('query', $query) }}">
        <button type="submit">Search</button>
    </form>

    {{-- hasilnya di sini --}}
    <div class="row">
        <div class="col-sm-5 col-md-6">
            @if(!empty($combinedList))
            <h2>Search Results:</h2>
            <ul>
                @foreach ($combinedList as $manga)
            <div>
                <a href="{{route('detailCommunity', ['manga_id' =>$manga['id']])}}">
                    <h2>{{ $manga['title'] }}</h2>
                </a>
                <img src="{{ $manga['image'] }}" alt="{{ $manga['title'] }}" style="height: 300px;width:300px">
                <h3>{{ $manga['count'] }} Posts</h3>
            </div>
            @endforeach
            </ul>
        @endif

        {{-- ini buat community yang udah terbentuk --}}
        @if (!empty($communities))
            @foreach ($communities as $community)
                <a href="{{route('detailCommunity', ['manga_id' =>$community['id']])}}">
                    <h2>{{ $community['title'] }}</h2>
                </a>
                <img src="{{ $community['image'] }}" alt="" style="height: 300px;width:300px">
                <h3>{{ $community['count'] }} Posts</h3>
            @endforeach
        @elseif ($query)

        @else
            Belum ada komunitas yang terbentuk!
        @endif
        </div>
    </div>

</script>
</body>
</html>
