<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\RoleClaims\Claims;
use App\Http\Requests\Auth\RoleClaims\RoleClaims;

/**
 * Check if ability (route name) is claimed by any of User's roles.
 * User's Roles & Claims should be declared in App\Http\Requests\Auth\RoleClaims\RoleClaims\RoleClaims
 *
 */
class RouteAuthorize
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $ability = Route::current()->getName();
        if ( ! RoleClaims::isClaimed( $user, $ability )) 
        {
            abort(403);
        }
        return $next($request);
    }
}
