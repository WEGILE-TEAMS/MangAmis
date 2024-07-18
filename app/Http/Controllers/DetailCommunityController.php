<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;

class DetailCommunityController extends Controller
{
    public function detailCommunity($manga_id){
        $details = Community::where('manga_id', $manga_id)->get();
        $count = $details->count();

        if($count > 0) {
            foreach($details as $detail){
                $user_id = $detail->user_id;
                $user = User::where('id', $user_id)->first();
                $username = $user->username;
                $comment = $detail->content;
                $community_id = $detail->id;

                if(isset($detail->image)) {
                    $image = $detail->image;
                } else {
                    $image = [];
                }
                $detailArray[] = [
                    'user_id'=> $user_id,
                    'comment'=> $comment,
                    'community_id'=> $community_id,
                    'username' => $username,
                    'image' => $image
                ];

            }
        } else {
            $detailArray = [];
        }

        return view('detailCommunity',['details'=>$detailArray,
            'manga_id' => $manga_id]);
    }

}
