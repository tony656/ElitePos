<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
   public function handle($request, Closure $next, ...$guards)
{
    $this->authenticate($request, $guards);

    $user = Auth::user();

    if (!$user || empty($user->levelStatus)) {
        return redirect()->route('login');
    }

    $role = $user->levelStatus; // Admin | Manager | Seller

    /*
    |--------------------------------------------------
    | ADMIN AREA CHECK
    |--------------------------------------------------
    */
        if (empty($role)) {
            abort(403, 'Not Authorized');
        }


    return $next($request);
}

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
