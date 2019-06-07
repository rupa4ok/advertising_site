<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use Illuminate\Http\Request;
use App\Services\Sms\SmsSender;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    use ThrottlesLogins;

    private $sms;

    public function __construct(SmsSender $sms)
    {
        $this->middleware('guest')->except('logout');
        $this->sms = $sms;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $this->loginRequest($request);
        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );
        if ($authenticate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();
            if ($user->isWait()) {
                Auth::logout();

                return back()->with('error', 'You need to confirm your account. Please check your email.');
            }
            if ($user->isPhoneAuthEnabled()) {
                Auth::logout();
                $token = (string) random_int(10000, 99999);
                $request->session()->put('auth', [
                    'id' => $user->id,
                    'token' => $token,
                    'remember' => $request->filled('remember'),
                ]);
                $this->sms->send($user->phone, 'Login code: '.$token);

                return redirect()->route('login.phone');
            }

            return redirect()->intended(route('cabinet.home'));
        }
        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }

    public function loginRequest($request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }
    }

    public function phone()
    {
        return view('auth.phone');
    }

    public function verify(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }
        $this->validate($request, [
            'token' => 'required|string',
        ]);
        if (! $session = $request->session()->get('auth')) {
            throw new BadRequestHttpException('Missing token info.');
        }
        /** @var User $user */
        $user = User::findOrFail($session['id']);
        if ($request['token'] === $session['token']) {
            $request->session()->flush();
            $this->clearLoginAttempts($request);
            Auth::login($user, $session['remember']);

            return redirect()->intended(route('cabinet.home'));
        }
        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['token' => ['Invalid auth token.']]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('home');
    }

    protected function username()
    {
        return 'email';
    }
}
