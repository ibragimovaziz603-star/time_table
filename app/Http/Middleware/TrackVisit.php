<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // Faqat GET so'rovlar uchun va admin panelga kirmaganlar uchun
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('api/*')) {
            Visit::create([
                'ip'         => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                'user_id'    => auth()->id(),
                'visited_at' => now(),
            ]);
        }

        return $next($request);
    }
}