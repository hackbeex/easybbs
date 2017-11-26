<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RecordLastActivedTime
{

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->recordLastActivedAt();
        }

        return $next($request);
    }
}
