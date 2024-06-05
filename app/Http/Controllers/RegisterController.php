<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class registerController extends Controller
{
    public function index() {
        return view('register.register', [
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
        User::create($validatedData);
        return redirect('/login')->with('success', 'Registration successfull! Please login');
    }
}