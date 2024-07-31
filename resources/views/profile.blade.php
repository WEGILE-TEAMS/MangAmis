@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
<section id="profile">
    <div class="profile-information">
        <div class="container">
            <div class="d-flex justify-content-between">
                <h5>Profile</h5>
                <div class="container-button" style="width: 150px !important;">
                    {{-- Logout button --}}
                    <div class="btn btn-secondary" style="padding: 10px !important;">
                        Logout
                    </div>
                </div>
            </div>
            <div class="profile-section mt-5">
                <div class="d-flex align-items-center">
                    <div class="photo-profile">
                        <div class="inner-img"></div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="name" class="form-control" id="username" placeholder="{{ Auth::user()->username }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" name="email" class="form-control" id="email" placeholder="{{ Auth::user()->user_email }}">
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
                    @if (!empty($bookmarks))
                    <div class="row d-flex justify-content-between align-items-center">
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
                            <div class="img" style="background-image: url('{{ $bookmark['image'] }}');"></div>
                            <div class="title">
                                {{ $bookmark['title'] }}
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="d-flex justify-content-center align-items-center" style="margin-bottom: 70px;">
                        <h5 style="color: #C11336;"> There are no bookmarked manga</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@include('template.footer')
@endsection