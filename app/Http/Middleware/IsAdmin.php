<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Faqat admin (role_id = 1)
        if (auth()->user()->role_id !== 1) {
            abort(403, 'Faqat administrator uchun.');
        }

        return $next($request);
    }
}