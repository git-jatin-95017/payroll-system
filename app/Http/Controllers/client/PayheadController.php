<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payhead;
// use DataTables;
// use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PayheadController extends Controller
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
	public function index(Request $request)
	{		
		return view('client.payhead.index');
	}

	public function getData(Request $request)
	{
		if ($request->ajax()) {
			$draw = $request->get('draw');
			$start = $request->get("start");
			$rowperpage = $request->get("length"); // total number of rows per page

			$columnIndex_arr = $request->get('order');
			$columnName_arr = $request->get('columns');
			$order_arr = $request->get('order');
			$search_arr = $request->get('search');

			$columnIndex = $columnIndex_arr[0]['column']; // Column index
			$columnName = $columnName_arr[$columnIndex]['data']; // Column name
			$columnSortOrder = $order_arr[0]['dir']; // asc or desc
			$searchValue = $search_arr['value']; // Search value

			// Total records
			$totalRecords = Payhead::select('count(*) as allcount')->count();
			$totalRecordswithFilter = Payhead::select('count(*) as allcount')->count();

			// Get records, also we have included search filter as well
			$records = Payhead::orderBy($columnName, $columnSortOrder)                	           
				->orWhere('payheads.name', 'like', '%' . $searchValue . '%')                
				->orWhere('payheads.description', 'like', '%' . $searchValue . '%')                
				->orWhere('payheads.pay_type', 'like', '%' . $searchValue . '%')                
				->skip($start)
				->take($rowperpage)
				->get();

			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $totalRecords,
				"iTotalDisplayRecords" => $totalRecordswithFilter,
				"aaData" => $records,
			);

			return response()->json($response);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('client.payhead.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'name' => 'required|max:255'
		]);

		Payhead::create([
			'name' => $data['name'],
			'description' => $data['description'],
			'pay_type' => $data['pay_type']
		]);		

		
		return redirect()->route('pay-head.index')->with('message', 'Payhead added successfully.');	
	}

	public function show(Payhead $payhead) {   
		return view('client.payhead.show', compact('leave'));
	}

	public function edit(Payhead $payhead)
	{
	   return view('client.payhead.edit', compact('leave'));
	}

	public function update(Request $request, Payhead $payhead)
	{
		$data = $request->all();

		$request->validate([
			'name' => 'required|max:255',
			'description' => 'required|max:500'
		]);

		$payhead->update([
			'name' => $data['name'],
			'description' => $data['description'],
			'pay_type' => $data['pay_type']
		]);		
	
		return redirect()->route('pay-head.index')->with('message', 'Payhead updated successfully.');	
	}   

	protected function permanentDelete($id){
		$trash = Payhead::find($id);

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

			return response()->json(['status'=>true, 'message'=>"Payhead deleted successfully."]);
		}
	}
}