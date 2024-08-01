@extends('template.master')

@section('title', 'Community Page')

@section('content')
@include('template.navbar')

<section id="community">
    <div class="layer1">
        <div class="container">
            <form action="{{ route('community') }}" class="d-flex align-items-center" method="GET">
                <div class="container-button" style="margin-right: 20px !important;">
                    <input class="form-control rounded-0" type="text" name="query" placeholder="Enter manga title" value="{{ old('query', $query) }}">
                    <div class="icon-search"></div>
                </div>
                <div class="container-button" style="width: fit-content !important;">
                    <button class="btn btn-primary" type="submit" style="padding: 7px 15px !important;">
                        Search                        
                    </button>
                </div>
            </form>
            <div class="manga-list">
                @if(!empty($combinedList))
                    <h2 class="text-light">Search Results:</h2>
                        @foreach ($combinedList as $manga)
                    <a href="{{route('detailCommunity', ['manga_id' =>$manga['id']])}}" class="manga-com" style="text-decoration: none;">
                        <div class="lines"></div>
                        <div class="manga-content">
                            <img src="{{ $manga['image'] }}" alt="">
                            <div class="text">
                                <h5>{{ $manga['title'] }}</h5>
                                <p>{{ $manga['count'] }} Posts</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                @endif

                @if (!empty($communities))
                    @foreach ($communities as $community)
                        <a href="{{route('detailCommunity', ['manga_id' =>$community['id']])}}" class="manga-com" style="text-decoration: none;">
                            <div class="lines"></div>
                            <div class="manga-content">
                                <img src="{{ $community['image'] }}" alt="">
                                <div class="text">
                                    <h5>{{ $community['title'] }}</h5>
                                    <p>{{ $community['count'] }} Posts</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @elseif ($query)

                @else
                    Belum ada komunitas yang terbentuk!
                @endif
            </div>
        </div>
    </div>
</section>

@include('template.footer')
@endsection