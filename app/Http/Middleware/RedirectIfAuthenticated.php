<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param  string|null  ...$guards
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	// public function handle(Request $request, Closure $next, ...$guards)
	// {
	//     $guards = empty($guards) ? [null] : $guards;

	//     foreach ($guards as $guard) {
	//         if (Auth::guard($guard)->check()) {
	//             return redirect(RouteServiceProvider::HOME);
	//         }
	//     }

	//     return $next($request);
	// }

	public function handle($request, Closure $next, $guard = null) {
	  if (Auth::guard($guard)->check()) {
		$user = Auth::user();
		
		// Check if this is an extra user (created by another client)
		if ($user->is_extra_user === 'Y' && $user->created_by) {
			// Log out the extra user
			Auth::logout();
			
			// Find the creator user
			$creatorUser = \App\Models\User::find($user->created_by);
			
			if ($creatorUser) {
				// Log in as the creator
				Auth::login($creatorUser);
				
				// Redirect based on creator's role
				switch ($creatorUser->role_id) {
					case 1:
						return redirect('/admin/dashboard');
					case 2:
						return redirect('/client/dashboard');
					case 3:
						return redirect('/employee/dashboard');
					default:
						return redirect('/home');
				}
			}
		}
		
		// Normal user flow
		$role = $user->role_id; 

		switch ($role) {
			case 1:
			  return redirect('/admin/dashboard');
			  break;
			case 2:
			  return redirect('/client/dashboard');
			  break;
			case 3:
			  return redirect('/employee/dashboard');
			  break;
			default:
			  return redirect('/home'); 
			break;
		}
	  }
	  return $next($request);
	}
}
