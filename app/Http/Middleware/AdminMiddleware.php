<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')
                ->with('error', 'Please log in first.');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}