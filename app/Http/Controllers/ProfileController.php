<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function show()
    {
        return view('profile', ['user' => Auth::user(),]);
    }

    public function update(Request $request)
    {
        $user=Auth::user();
        $request->validate([
            'username' => 'nullable|string|max:255',
            'user_email' => 'unique:users|nullable|email|max:255',
            'user_password' => 'nullable|string|min:5',
        ]);

        if ($request->filled('username') && $request->username != $user->username) {
            $user->username = $request->username;
        }else{
            $user->username=$user->username;
        }

        if ($request->filled('user_email') && $request->user_email != $user->user_email) {
            $user->user_email = $request->user_email;
        }else{
            $user->user_email=$user->user_email;
        }
        if (!empty($request->user_password)) {
            $user->user_password = Hash::make($request->user_password);
        }

        $user->save();
        // anjai
        return redirect()->back()->with('success','User Profile Berhasil di Update!');

    }
}
