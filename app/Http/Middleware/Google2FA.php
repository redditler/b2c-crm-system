<?php

namespace App\Http\Middleware;
use App\Support\Google2FAAuthentication;
use Closure;
use Illuminate\Support\Facades\Auth;

class Google2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_null(Auth::user()->passwordSecurity) || empty(Auth::user()->passwordSecurity)){
            return redirect('/2fa');
        }
        $authenticator = app(Google2FAAuthentication::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
