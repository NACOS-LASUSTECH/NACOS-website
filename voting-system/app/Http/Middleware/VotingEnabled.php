<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VotingEnabled
{
    /**
     * Handle an incoming request — check if voting is enabled.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Setting::isVotingEnabled()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Voting is currently disabled.'], 403);
            }
            return redirect()->route('home')->with('error', 'Voting is currently disabled.');
        }

        return $next($request);
    }
}
