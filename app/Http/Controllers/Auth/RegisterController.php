<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Str;

class RegisterController extends Controller
{
    use RegistersUsers;
    
    protected $redirectTo = '/';
    private $service;
    
    public function __construct(RegisterService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
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
