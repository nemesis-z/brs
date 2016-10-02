<?php

namespace App\Http\Middleware;

use Closure;
use App\Facades\Helper;
use Illuminate\Support\Facades\Auth;

class IsSystemClosed
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
        if(Helper::closed()) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson())return response()->json(['logout'=>1]);
            else return redirect('login');
        }
        return $next($request);
    }
}
