<?php

namespace Cms\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $home = '/'.config('cms.path_prefix');

        if (Auth::guard(config('cms.guard'))->check()) {
            return redirect($home);
        }

        return $next($request);
    }
}
