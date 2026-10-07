<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStarosta
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role_id !== 3) {
            abort(403, 'Faqat talabalar uchun.');
        }

        if (!auth()->user()->isStarosta()) {
            abort(403, 'Siz starosta emassiz.');
        }

        return $next($request);
    }
}