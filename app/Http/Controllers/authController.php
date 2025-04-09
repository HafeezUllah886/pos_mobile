<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function login(Request $req)
    {

       if(auth()->attempt($req->only('name', 'password')))
       {
            $req->session()->regenerate();
            return redirect()->intended('/');
       }
       return back()->with('error', 'Wrong User Name or Password');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
