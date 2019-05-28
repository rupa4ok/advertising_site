<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    protected $redirectTo = '/cabinet';
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login()
    {
    
    }
    
    public function authenticated(Request $request, $user)
    {
//        if(!$user->status !== User::STATUS_ACTIVE) {
//            $this->guard()->logout();
//            return back()->with('error', 'Confirm your account');
//        }
//        return redirect()->intended($this->redirectPath());
    }
}
