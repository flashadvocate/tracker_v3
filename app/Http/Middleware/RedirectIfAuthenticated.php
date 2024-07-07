<?php

namespace App\Http\Middleware;

use Closure;
use Facades\App\AOD\ClanForumSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  null|string  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (ClanForumSession::exists()) {
            return redirect()->intended();
        }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
