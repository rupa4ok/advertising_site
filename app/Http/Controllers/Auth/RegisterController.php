<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyMail;
use App\Models\User;
use App\Http\Controllers\Controller;
use Composer\DependencyResolver\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;
    
    protected $redirectTo = '/';
    
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    protected function create(RegisterRequest $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT
        ]);
        
//        Mail::to($user->email)->send(new VerifyMail($user));
    
        return $user;
    }
}
