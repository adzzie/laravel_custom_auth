<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    /**
     * @return view
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     *
     * @return view
     */
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($validate)){
            return back()->with('fail','email or password wrong!');
        }

        return redirect('/')->with('success','success login');
        // return $request->input();
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $request['password'] = Hash::make($request->password);
        Log::debug($request);
        $user = User::create($request->all());

        if(!$user){
            return back()->with('fail','Something went wrong!');
        }

        return redirect('login')->with('success', 'Success create new account!');

    }

    public function logout(Request $request)
    {
        if(Auth::check()){
            Auth::logout();
        }
        return redirect('/');
    }
}

