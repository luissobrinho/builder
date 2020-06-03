<?php

namespace App\Http\Middleware;

use Closure;
use Barryvdh\Debugbar\Facade as DebugBar;
use Illuminate\Http\Request;

class DebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DebugBar::disable();

        if(auth()->check()) {
            if(auth()->user()->hasPermission('dev')) {
                Debugbar::enable();
            }
        }

        return $next($request);
    }
}
