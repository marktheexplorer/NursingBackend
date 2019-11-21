<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BlockedUser
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
        if(Auth::user()->is_blocked == 1){
            Auth::user()->OauthAcessToken()->delete();
            return response()->json(['status_code' => 999, 'message' => 'Your account is blocked by admin. Please contact to admin: admin@gmail.com.', 'data' => null]);
          }
        return $next($request);
    }
}
