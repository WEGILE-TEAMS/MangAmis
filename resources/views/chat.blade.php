@extends('template.master')

@section('title', 'Community Page')

@section('content')
    @include('template.navbar')

    <section id="detail-room">
        <div class="container main-content">
            <div class="room-card d-flex align-items-end justify-content-between">
                <div class="left">
                    <h5>{{ $community['comment'] }}</h5>
                    <div class="details-room d-flex justify-content-between align-items-center">
                        <span style="margin-right: 30px;">Created by: {{ $community['username'] }}</span>
                        <span>Created Date: {{ $community['date'] }}</span>
                    </div>
                    @php
                    $imagePath = $community['image'] ?? null;
                    $exists = $imagePath && Storage::disk('public')->exists($imagePath);
                    $fileUrl = $exists ? Storage::url($imagePath) : null;
                    @endphp
                    @if ($community['image'])
                    <div class="container-button">
                        <img src="{{ $fileUrl }}" alt="">
                    </div>
                    @endif
                </div>
            </div>

            @foreach ($chats as $chat)
                <div class="chat-section">
                    <div class="chat-item d-flex align-items-end {{ ($chat['user_id'] != Auth::user()->id) ? 'other' : '' }}">
                        <div class="left">
                            <div class="nameDate d-flex justify-content-between">
                                <div class="name" style="margin-right: 30px">{{ $chat['username'] }}</div>
                                <div class="date">{{ $chat['date'] }}</div>
                            </div>
                            <div class="text-box" style="max-width: 30vw;">
                                {{ $chat['comment'] }}
                            </div>
                        </div>
                        @if ($chat['user_id'] == Auth::user()->id)
                            <form action="{{ route('chat.destroy', ['chat_id' => $chat['chat_id'], 'community_id' => $community_id ]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <div class="right">
                                    <button type="submit" class="btn btn-primary">
                                        Delete
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
        <form class="input-chat d-flex justify-content-center align-items-center" action="{{ route('addChat') }}" method="POST">
            @csrf
            <input type="hidden" name="community_id" value="{{ $community_id }}">
            <div class="container-button">
                <input type="text" name="comment" class="input-control" placeholder="Type Here...">
                <button type="submit">
                    <img src="/images/icon-send.png" alt="">
                </button>
            </div>
        </form>
    </section>

    @include('template.footer')
@endsection
