<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            !$user->profile_completed &&
            !$request->is('api/me/complete-profile')
        ) {
            return response()->json([
                'message' => 'Complete your profile first.'
            ], 403);
        }

        return $next($request);
    }
}
