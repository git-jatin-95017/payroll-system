<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin bypass (you can customize this logic)
        if ($user->role_id === 1 || $user->email === 'admin@payroll.com') {
            return $next($request);
        }

        // Check if user has any permission for the module
        if (!$user->hasModulePermission($module)) {
            // Return 403 Forbidden or redirect to unauthorized page
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this module.',
                    'module_required' => $module
                ], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access the ' . ucfirst($module) . ' module.');
        }

        return $next($request);
    }
}