@extends('template/master')

@section('title')
Edit Profile
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('Style/css/main.css') }}" />
@endsection

@section('content')
<h2>Edit Profile</h2>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('profile.update') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" name="password">
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation">
    </div>

    <button type="submit">Update Profile</button>
</form>
@endsection
