@extends('template.master')

@section('title', 'Community Page')

@section('content')
@include('template.navbar')

<section id="room-lists">
    <div class="container" style="padding-top: 10%">
        <form action="{{ route('addCommunity') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 70px;">
            @csrf
            <input type="hidden" name="manga_id" value="{{ $manga_id }}">
            <div class="d-flex align-items-center">
                <div class="form-group" style="width: 100vw; margin-bottom: 30px; margin-right: 50px;">
                    <label class="text-white" for="content_{{ $manga_id }}" style="font-size: 30px;">Add Comment</label>
                    <textarea class="form-control" id="content_{{ $manga_id }}" name="content" rows="3" style="width: 100%;"></textarea>
                </div>
                <div class="container-upload-file container-button">
                    <label for="input-file" id="drop-area" class="btn btn-secondary" style="padding: 10px !important; margin: 0 !important;">
                        <input type="file" id="input-file" class="form-control @error('input-file') is-invalid @enderror" accept="image/*, video/*" name="image" hidden>
                        <div class="img-view">
                            <img src="{{ asset('images/file-add.png') }}" alt="">
                        </div>
                        @error('image')
                        <div class="invalid-feedback mt-3">
                            {{ $message }}
                        </div>
                        @enderror
                    </label>
                </div>
            </div>
            <div class="text-center">    
                <div class="container-button">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
        <div class="lines"></div>
        @if (!empty($details))
        @foreach ($details as $detail)
        <div class="room-card d-flex align-items-end justify-content-between mb-5" >
            <div class="left">
                <h5>{{ $detail['comment'] }}</h5>
                <div class="details-room d-flex justify-content-between align-items-center">
                    <span>Created by: {{ $detail['username'] }}</span>
                    <span style="width: 30px;"></span>
                    <span>Created Date: {{ $detail['date'] }}</span>
                </div>
                @php
                $imagePath = $detail['image'] ?? null;
                $exists = $imagePath && Storage::disk('public')->exists($imagePath);
                $fileUrl = $exists ? Storage::url($imagePath) : null;
                @endphp
                @if($detail['image'])
                <div class="container-button">
                    <img src="{{ $fileUrl }}" alt="">
                </div>
                @endif
            </div>
            <div class="right">
                <a href="{{ route('viewChat', ['community_id' => $detail['community_id']]) }}" class="container-button">
                    <button class="btn btn-primary">
                        Go to Room
                    </button>
                </a>
            </div>
        </div>
        <div class="lines"></div>
        @endforeach
    @else
    <form action="{{ route('addCommunity') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 70px;">
        @csrf
        <label class="text-white" for="content_{{ $manga_id }}" style="font-size: 30px;">Add Comment</label>
        <div class="d-flex align-items-center">
            <input type="hidden" name="manga_id" value="{{ $manga_id }}">
            <div class="form-group" style="width: 100vw; margin-bottom: 30px; margin-right: 50px;">
                <textarea class="form-control" id="content_{{ $manga_id }}" name="content" rows="3" style="width: 100%;"></textarea>
            </div>
            <div class="container-upload-file container-button">
                <label for="input-file" id="drop-area" class="btn btn-secondary" style="padding: 10px !important; margin: 0 !important;">
                    <input type="file" id="input-file" class="form-control @error('input-file') is-invalid @enderror" accept="image/*, video/*" name="image" hidden>
                    <div class="img-view">
                        <img src="{{ asset('images/file-add.png') }}" alt="">
                    </div>
                    @error('image')
                    <div class="invalid-feedback mt-3">
                        {{ $message }}
                    </div>
                    @enderror
                </label>
            </div>
        </div>
        <div class="text-center">    
            <div class="container-button">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>
    @endif
    </div>
</section>

@include('template.footer')
@endsection
{{-- 
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
        <h6>{{ $detail['date'] }}</h6>
        <a href="{{ route('viewChat', ['community_id' => $detail['community_id']]) }}">
            <div class="room">
                <p>Topik : {{$detail['comment']}}</p>
                @php
                $imagePath = $detail['image'] ?? null;
                $exists = $imagePath && Storage::disk('public')->exists($imagePath);
                $fileUrl = $exists ? Storage::url($imagePath) : null;
                @endphp
                @if($detail['image'])
                <img src="{{ $fileUrl }}" alt="" style="height: 200px; width: 300px">
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
</html> --}}
