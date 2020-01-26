<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


class IsAnalyst
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(Auth::user()->analyst);
        if (Auth::user()->analyst) {
            return $next($request);
        } elseif (Route::current()->getName() == 'users.show' || Route::current()->getName() == 'users.edit' || Route::current()->getName() == 'users.update') {
            return $next($request);
        }

        return redirect('/'); // If user is not an admin.
    }
}
