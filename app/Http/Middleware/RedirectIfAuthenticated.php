<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role->name;
                if ($role === 'kasir') {
                    return redirect()->route('pos');
                } elseif ($role === 'gudang') {
                    return redirect()->route('purchase-orders.index');
                }
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}

