<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {
        return view('register', [
            'title' => 'Register',
        ]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'username' => 'required|max:255',
            'user_email' => 'required|email:dns|unique:users',
            'user_password' => 'required|min:5|max:255',
        ]);

        $validatedData['user_password'] = Hash::make($validatedData['user_password']);
        // $request->session()->flash('success', 'Registration successfull! Please login');
        $user = User::create($validatedData);
        if($user){
            return redirect('/login')->with('success', 'Registration successfull! Please login');
        }else{
            return redirect('/register')->with('error', 'Registration failed! Try again');
        }
    }
}
