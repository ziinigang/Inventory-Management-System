<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // If it's an API request, return null (triggers JSON 401 response)
        // If it's a web request, redirect to login page
        return $request->expectsJson() ? null : route('login');
    }
}