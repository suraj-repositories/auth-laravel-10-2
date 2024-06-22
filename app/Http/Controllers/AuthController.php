<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage(){
        return view("login");
    }
    public function signupPage(){
        return view("signup");
    }
    
    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:3'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if($user && Hash::check($validated['password'], $user->password)){
            Auth::login($user);
            return redirect("/")->with('success', 'Successfully Logged In');
        }
        return redirect('/login')->with('error',  'Wrong Credentials!');
    }

    public function signup(Request $request){
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed'
        ]);

        $user = User::create([
            'name'=> $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->assignRole('admin');

        Auth::login($user);
        return redirect('/')->with('success', 'signup successful!');
    }


    public function logout(){
        Auth::logout();
        return redirect('/login')->with('success', "Logged out Successfully!");
      }

    
}
