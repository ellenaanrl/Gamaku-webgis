<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ← ini penting

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        

        // Check if email ends with @mail.ugm.ac.id
        $email = Auth::user()->email ?? '';
        if (!str_ends_with($email, '@mail.ugm.ac.id')) {
            abort(403, 'Access restricted to @mail.ugm.ac.id email addresses.');

            if (empty(Auth::user()->role)) {
                abort(403, 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
