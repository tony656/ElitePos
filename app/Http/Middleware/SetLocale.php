<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale') && in_array(session('locale'), ['en', 'sw'])) {
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
}
