<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if (!empty($chats))
        @foreach ($chats as $chat)
            <div class="room">
                <h6>Username: {{ $chat['username'] }}</h6>
                <h6>{{ $chat['date'] }}</h6>
                <p>Chat : {{$chat['comment']}}</p>
            </div>
            <div>
                @if ($chat['user_id'] == Auth::user()->id)
                    <form action="{{route('chat.destroy', ['chat_id'=> $chat['chat_id'],
                    'community_id' => $community_id ])}}"
                        method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger ml-3">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
        <form action="{{ route('addChat') }}" method="POST">
            @csrf
            <input type="hidden" name="community_id" value="{{ $community_id }}">
            <div class="form-group">
                <label for="comment_{{ $community_id }}">Comment</label>
                <textarea class="form-control" id="comment_{{ $community_id }}" name="comment" rows="3"></textarea>
            </div>
            <button type="submit">Tambah</button>
        </form>
    @else
    <form action="{{ route('addChat') }}" method="POST">
        @csrf
        <input type="hidden" name="community_id" value="{{ $community_id }}">
        <div class="form-group">
            <label for="comment_{{ $community_id }}">Comment</label>
            <textarea class="form-control" id="comment_{{ $community_id }}" name="comment" rows="3"></textarea>
        </div>
        <button type="submit">Tambah</button>
    </form>
    @endif
</body>
</html>