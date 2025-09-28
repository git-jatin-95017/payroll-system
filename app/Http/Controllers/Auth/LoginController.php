<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	// protected $redirectTo = '/admin/dashboard';//RouteServiceProvider::HOME;

	public function redirectTo() {
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
						return '/admin/dashboard';
					case 2:
						return '/client/dashboard';
					case 3:
						return '/employee/dashboard';
					default:
						return '/home';
				}
			}
		}
		
		// Normal user flow
		$role = $user->role_id; 
		switch ($role) {
			case 1:
			  return '/admin/dashboard';
			  break;
			case 2:
			  return '/client/dashboard';
			  break;
			case 3:
			  return '/employee/dashboard';
			  break;
			default:
			  return '/home'; 
			break;
		}
	}
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}
}
