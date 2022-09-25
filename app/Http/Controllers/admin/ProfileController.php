<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('admin.profile.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id = NULL)
	{
		$id = auth()->user()->id;

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'unique:users,email,'.$id.'|email|required|max:191',
			// 'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$id],
			'phone_number' => ['required']                  
		]);
		
		

		$admin = User::findOrFail($id);

		if (!empty($request->password)) {
			$request['password'] =  Hash::make($request->password);
		} else {
			unset($request['password']);
		}

		$admin->update($request->all());

		return back()->with('message','Profile updated successfully.');
	}
}
