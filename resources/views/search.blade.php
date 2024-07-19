@extends('template/master')

@section('title')
Search Page
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('Style/css/main.css')}}" />
@endsection

@section('content')
<form action="{{ route('manga.search') }}" method="GET">
    <input type="text" name="query" placeholder="Enter manga title" value="{{ old('query', $query) }}">
    <button type="submit">Search</button>
</form>


@if(request()->has('query') && !empty($combinedList))
<h2>Search Results:</h2>
    <ul>
        @foreach($combinedList as $manga)
            <li>
                <h3>{{ $manga['title']}}</h3>
                <p>{{ $manga['description']}}</p>
                <img src="{{ $manga['image'] }}" alt="">
            </li>
        @endforeach
    </ul>
@endif
@endsection
