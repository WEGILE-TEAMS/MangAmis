@extends('template/master')

@section('title')
Login Page
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/css/main.css') }}" />
@endsection

@section('content')
<h2>Profile</h2>

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

        <div>
            <button type="submit">Save</button>
        </div>
    </form>
@endsection
