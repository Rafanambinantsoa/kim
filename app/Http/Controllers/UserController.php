<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function logout(){
        Auth::logout();
        return redirect('/')->with('success', 'You are logged out');
    }

    public function showCorrectHomePage(){
        if (auth()->check()){
            return view('homePage-feed');
        }else{
            return view('homePage');
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required']);

        if (Auth::attempt(['username' => $data['loginusername'], 'password' =>$data['loginpassword']])) {
            return redirect("/")->with('success', 'You are logged in');
        }
        else{
            return redirect('/')->with('error', 'Wrong username or password');
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'password' => ['required', 'min:8']
        ]);

        $data['password'] = bcrypt($data['password']);
        // dd($data);
        $user = User::create($data);
        //Auto login
        auth()->login($user);
        return redirect('/')->with('success' , 'Thanks for registering');
    }
}
