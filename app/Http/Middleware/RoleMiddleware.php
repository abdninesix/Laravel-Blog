<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401); // Not logged in
        }

        if ($user->role !== $role) {
            // Optional: custom redirect for admin users
            if ($user->role === 'admin') {
                return redirect()->route('admin.posts.index'); // Make sure route exists
            } elseif ($user->role === 'user') {
                return redirect()->route('user.posts.index');
            }

            abort(403); // Forbidden for everyone else
        }
        
        return $next($request);
    }
}
