@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
<section id="profile">
    <div class="profile-information">
        <div class="container">
            <h5>Profile</h5>
            <div class="profile-section mt-5">
                <div class="d-flex align-items-center">
                    <div class="photo-profile">
                        <div class="inner-img"></div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="name" class="form-control" id="username" placeholder="Vein Chaya">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" name="email" class="form-control" id="email" placeholder="veinchaya@gmail.com">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="text" name="password" class="form-control" id="password">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="container-button">
                        <button class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bookmark-section">
        <div class="container">
            <div id="new-update">
                <div class="container">
                    <h5>BOOKMARKED MANGA</h5>
                    <div class="lines"></div>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="manga-card d-flex flex-column">
                            <div class="img" style="background-image: url('images/dandan-book.jpg');"></div>
                            <div class="title">
                                Dandandan
                            </div>
                            <div class="chp-title">
                                Chapter 110 : Beginning after the en...
                            </div>
                        </div>
                        <div class="manga-card d-flex flex-column">
                            <div class="img" style="background-image: url('images/kaijuu-cover.jpg');"></div>
                            <div class="title">
                                Kaijuu No.8
                            </div>
                            <div class="chp-title">
                                Chapter 110 : Beginning after the en...
                            </div>
                        </div>
                        <div class="manga-card d-flex flex-column">
                            <div class="img" style="background-image: url('images/86-books.jpg');"></div>
                            <div class="title">
                                86
                            </div>
                            <div class="chp-title">
                                Chapter 110 : Beginning after the en...
                            </div>
                        </div>
                        <div class="manga-card d-flex flex-column">
                            <div class="img" style="background-image: url('images/twaf.jpg');"></div>
                            <div class="title">
                                The World After The Fall
                            </div>
                            <div class="chp-title">
                                Chapter 110 : Beginning after the en...
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 90px;">
                        <div class="text-center">
                            <div class="container-button">
                                <button class="btn btn-secondary">See More</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('template.footer')
@endsection<!DOCTYPE html>
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
