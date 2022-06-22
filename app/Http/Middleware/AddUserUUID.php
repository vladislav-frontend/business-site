<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AddUserUUID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_uuid = ($request->cookie('user_uuid')?:$request->session()->get('user_uuid'))?:Str::uuid()->toString();
        Cookie::queue('user_uuid', $user_uuid, 43800);
        $request->session()->put('user_uuid', $user_uuid);

        return $next($request);
    }
}
