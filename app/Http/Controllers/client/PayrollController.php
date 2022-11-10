<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Builder $builder)
	{

		return view('payroll.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('client.payroll.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'title' => 'required|max:255',		
			'description' => ['required', 'max:500'],
			'holiday_date' => ['required', 'date'],
			'type' => ['required']
		]);

		Holiday::create([
			'title' => $data['title'],
			'description' => $data['description'],
			'holiday_date' => $data['holiday_date'],
			'type' => $data['type']
		]);		

		
		return redirect()->route('holidays.index')->with('message', 'Holiday created successfully.');	
	}

	public function show(Holiday $holiday) {   
		return view('holidays.show', compact('holiday'));
	}

	public function edit(Holiday $holiday)
	{
	   return view('holidays.edit', compact('holiday'));
	}

	public function update(Request $request, Holiday $holiday)
	{
		$data = $request->all();

		$request->validate([
			'title' => 'required|max:255',		
			'description' => ['required', 'max:500'],
			'holiday_date' => ['required', 'date'],
			'type' => ['required']
		]);

		$holiday->update([
			'title' => $data['title'],
			'description' => $data['description'],
			'holiday_date' => $data['holiday_date'],
			'type' => $data['type']
		]);		
	
		return redirect()->route('holidays.index')->with('message', 'Holiday updated successfully.');	
	}   

	protected function permanentDelete($id){
        $trash = Holiday::find($id);

        $trash->delete();

        return true;
    }

   	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{
		if (request()->ajax()) {
			 $trash = $this->permanentDelete($id);

			return response()->json(['status'=>true, 'message'=>"Holiday deleted successfully."]);
		}
	}
}