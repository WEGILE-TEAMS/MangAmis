@extends('template.master')

@section('title', 'Home Page')

@section('content')
@include('template.navbar')
<section id="profile">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="profile-information">
        <div class="container">
            <div class="row">
                <h5 class="col-6">Profile</h5>
                <div class="container-button col-6">
                    <form action="/logout" method="POST" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-secondary" href="#">Sign Out</button>
                    </form>
                </div>
            </div>
            <div class="profile-section mt-5">
                <div class="d-flex align-items-center">
                    <div class="photo-profile">
                        <div class="inner-img"></div>
                    </div>
                    <div class="form-section">
                    <form action="{{ route('profile.update') }}" method="POST" >
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="Username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="{{  $user->username }}">
                        </div>

                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="{{$user->user_email }}" >
                        </div>

                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="password" class="form-control" id="user_password" name="user_password" placeholder="">
                        </div>

                        <div class="text-end">
                            <div class="container-button">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
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
                            @if (!empty($bookmarks))

                            @endif
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
                            ]) }}">
                                <div class="img" style="background-image: url('{{ $bookmark['image'] }}');"></div>
                                <div class="title">
                                    {{ $bookmark['title'] }}
                                </div>
                                <div class="chp-title">
                                    Chapter 110 : Beginning after the en...
                                </div>
                            </a>
                            @endforeach
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
