<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedOrganizerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role !== 'organizer') {
            abort(403, 'Unauthorized access.');
        }

        if ($user->organizer_status === 'pending') {
            return redirect()->route('organizer.pending');
        }

        if ($user->organizer_status === 'rejected') {
            return redirect()->route('organizer.rejected');
        }

        if ($user->organizer_status !== 'approved') {
            return redirect()->route('organizer.pending');
        }

        return $next($request);
    }
}