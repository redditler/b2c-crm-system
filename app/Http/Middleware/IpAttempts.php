<?php

namespace App\Http\Middleware;

use App\Installer;
use Closure;
use App\LeedIps;
use Illuminate\Support\Facades\Route;

class IpAttempts
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
//        dd($request->ip());
//        dd(Route::current()->getName());
        if ($request->ip()) {

            if (Route::current()->getName() == 'add-worker') {
                $ip = Installer::where('ip', '=', $request->ip())->latest()->first();
            } else {
                $ip = LeedIps::where('client_ip', '=', $request->client_ip)->latest()->first();
            }
            if ($ip) {
                $last = $ip->created_at->timestamp;
                $now = strtotime("now");
                //time limit 1 min
                if ($now - $last <= 60) {
                    $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error. \r\n Too many requests from - " . $request->client_ip;
                    $f = fopen("log.txt", "a+");
                    fwrite($f, $forLog);
                    fclose($f);
                    return response()->json(['status' => 'error', 'message' => 'Too many requests.'], 429);
                } else {
                    return $next($request);
                }
            } else {
                return $next($request);
            }
        } else {
            $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error. \r\n no ip.";
            $f = fopen("log.txt", "a+");
            fwrite($f, $forLog);
            fclose($f);
            return response()->json(['status' => 'error', 'message' => 'validation error. no ip.'], 400);
        }
        return $next($request);
    }
}
