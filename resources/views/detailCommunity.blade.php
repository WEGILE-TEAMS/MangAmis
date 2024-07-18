<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<style>
    .img-view{
        background-color: gray;
    }
</style>
<body>
    <p>Manga ID: {{ $manga_id }}</p>
    @if (!empty($details))
        @foreach ($details as $detail)
        <h2>Pembuat Post: {{ $detail['username'] }}</h2>
        <a href="{{ route('viewChat', ['community_id' => $detail['community_id']]) }}">
            <div class="room">
                <p>Topik : {{$detail['comment']}}</p>
                @if($detail['image'])
                <img src="{{ asset('storage/' . $detail['image']) }}" alt="" style="height: 200px; width: 300px">
                @endif
            </div>
        </a>
        @endforeach
        <form action="{{ route('addCommunity') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="manga_id" value="{{ $manga_id }}">
            <div class="form-group">
                <label for="content_{{ $manga_id }}">Comment</label>
                <textarea class="form-control" id="content_{{ $manga_id }}" name="content" rows="3"></textarea>
            </div>
            <div class="container-upload-file">
                <label for="input-file" id="drop-area">
                    <input type="file" id="input-file" class="form-control @error('input-file') is-invalid @enderror" accept="image/*, video/*" name="image" hidden>
                    <div class="img-view">
                        <p>Masukkan<br> Gambar/Video</p>
                    </div>
                    @error('image')
                    <div class="invalid-feedback mt-3">
                        {{ $message }}
                    </div>
                    @enderror
                </label>
            </div>
            <button type="submit">Tambah</button>
        </form>
    @else
    <form action="{{ route('addCommunity') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="manga_id" value="{{ $manga_id }}">
        <div class="form-group">
            <label for="content_{{ $manga_id }}">Comment</label>
            <textarea class="form-control" id="content_{{ $manga_id }}" name="content" rows="3"></textarea>
        </div>
        <div class="container-upload-file">
            <label for="input-file" id="drop-area">
                <input type="file" id="input-file" class="form-control @error('input-file') is-invalid @enderror" accept="image/*, video/*" name="image" hidden>
                <div class="img-view">
                    <p>Masukkan<br> Gambar/Video</p>
                </div>
                @error('image')
                <div class="invalid-feedback mt-3">
                    {{ $message }}
                </div>
                @enderror
            </label>
        </div>
        <button type="submit">Tambah</button>
    </form>
    @endif

</body>
</html>
