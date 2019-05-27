<?php

namespace App\Http\Controllers\Cabinet;

use App\Services\Sms\SmsSender;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhoneController extends Controller
{
    private $sms;
    
    public function __construct(SmsSender $sms)
    {
        $this->sms = $sms;
    }
    
    public function request(Request $request)
    {
        $user = Auth::user();
        
        try {
            $token = $user->requestPhoneVerification(Carbon::now());
            $this->sms->send($user->phone, 'Phone verification token:' . $token);
        } catch (\DomainException $e) {
            $request->session()->flash('error', $e->getMessage());
        }
        
        return redirect()->route('cabinet.profile.phone');
    }
    
    public function form()
    {
        $user = Auth::user();
        
        return view('cabinet.profile.phone', compact('user'));
    }
    
    public function verify(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|max:255'
        ]);
    
        $user = Auth::user();
        
        try {
            $user->verifyPhone($request['token'], Carbon::now());
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.phone')->with('error', $e->getMessage());
        }
        
        return redirect()->route('cabinet.profile.home');
    }
}
