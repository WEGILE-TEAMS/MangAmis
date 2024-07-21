<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function viewChat($community_id){
        $chats = Chat::where('community_id', $community_id)->get();
        $count = $chats->count();
        $user_id = Auth::id();
        if($count > 0) {
            foreach($chats as $chat){
                $user_id = $chat->user_id;
                $user = User::where('id', $user_id)->first();
                $username = $user->username;
                $user_id=$chat->user_id;
                $comment = $chat->comment;
                $community_id=$chat->community_id;
                $chatArray[]=[
                    'user_id'=>$user_id,
                    'comment'=>$comment,
                    'community_id'=>$community_id,
                    'username' => $username,
                    'chat_id' => $chat->id
                ];

            }
        } else {
            $chatArray = [];
        }
        return view('chat',['chats'=>$chatArray,
            'community_id' => $community_id, 'user_id' => $user_id]);
    }

    public function addChat(Request $request) {
        $credentials = $request->validate([
            'community_id' => 'required',
            'comment' => 'required',
        ]);
        $credentials['user_id'] = Auth::user()->id;
        // dd($credentials);
        $chat = Chat::create($credentials);

        return redirect()->route('viewChat', ['community_id' => $credentials['community_id']])->with('success', 'Berhasil nambah chat');
    }

    public function destroy(Chat $chat_id, $community_id) {
        $chat_id->delete();
        return redirect()->route('viewChat', ['community_id' => $community_id])->with('delete', "Hapus data chat berhasil");
    }
}
