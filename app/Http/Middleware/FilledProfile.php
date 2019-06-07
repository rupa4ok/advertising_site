<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FilledProfile
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasFilledProfile()) {
            return redirect()
                ->route('cabinet.profile.home')
                ->with('error', 'Please fill you name and verify your phone');
        }

        return $next($request);
    }
}
