<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payhead;
use App\Models\Paystructure;
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
		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$payheads = Payhead::orderBy('payheads.id', 'desc')
			->where('payheads.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query
					->where(function ($query) use ($searchValue) {
						$query->where('payheads.name', 'like', '%' . $searchValue . '%')
							->orWhere('payheads.description', 'like', '%' . $searchValue . '%')
							->orWhereRaw("
								CASE 
									WHEN payheads.pay_type = 'nothing' THEN 'Addition to Net Pay'
									WHEN payheads.pay_type = 'deductions' THEN 'Deduction to Net Pay'
									WHEN payheads.pay_type = 'earnings' THEN 'Addition to Gross Pay'
								END LIKE ?", ['%' . $searchValue . '%']);
					});

			})
			->paginate(10);

		return view('client.payhead.index', compact('payheads'));
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
			$totalRecords = Payhead::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();
			$totalRecordswithFilter = Payhead::select('count(*) as allcount')->where('created_by', auth()->user()->id)->count();

			// Get records, also we have included search filter as well
			$records = Payhead::orderBy($columnName, $columnSortOrder)   
				->where('payheads.created_by', auth()->user()->id)             	 
				->where(function ($query) use($searchValue) {
			        $query
			        	->orWhere('payheads.name', 'like', '%' . $searchValue . '%')                
						->orWhere('payheads.description', 'like', '%' . $searchValue . '%')                
						->orWhere('payheads.pay_type', 'like', '%' . $searchValue . '%');
			    })                
				->skip($start)
				->take($rowperpage)
				->get();

			foreach($records as $key => $val) {
				if ($val->pay_type == 'nothing') {
					$records[$key]['pay_type'] = 'Addition to Net Pay';
				}
				if ($val->pay_type == 'deductions') {
					$records[$key]['pay_type'] = 'Deduction to Net Pay';
				}
				if ($val->pay_type == 'earnings') {
					$records[$key]['pay_type'] = 'Addition to Gross Pay';
				}
			}

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
		return view('client.payhead.show', compact('payhead'));
	}

	public function edit($id)
	{
		$payhead = Payhead::find($id);
	   	
	   	return view('client.payhead.edit', compact('payhead'));
	}

	public function update(Request $request, $id)
	{
		$data = $request->all();

		$payhead = Payhead::find($id);
		
		$request->validate([
			'name' => 'required|max:255',
			'description' => 'required|max:500'
		]);

		$payhead->update([
			'name' => $data['name'],
			'description' => $data['description'],
			'pay_type' => $data['pay_type']
		]);		
	
		return redirect()->route('pay-head.index')->with('message', 'Pay label updated successfully.');	
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

	public function assign(Request $request) {
		if (request()->ajax()) {
			$result = array();
			$requestData =$request->all();

			$payheads = $requestData['selected_payheads'];
			// $default_salary = $requestData['pay_amounts'];
			$emp_code = $requestData['empcode'];
			
			$checkSQL = Paystructure::where('user_id', $emp_code);
			// if ( $checkSQL->count()  > 0) {
				if ( !empty($payheads) && !empty($emp_code) ) {
					if ( $checkSQL->count() == 0 ) {
						foreach ( $payheads as $payhead ) {
							Paystructure::create([
								'user_id' => $emp_code,
								'payhead_id' => $payhead,
								'default_salary' => 0
							]);
						}
						$result['result'] = 'Paylabels are successfully assigned to employee.';
						$result['code'] = 0;
					} else {
						Paystructure::where('user_id', $emp_code)->delete();						
						foreach ( $payheads as $payhead ) {
							Paystructure::create([
								'user_id' => $emp_code,
								'payhead_id' => $payhead,
								'default_salary' => 0
							]);
						}
						$result['result'] = 'Paylabels are successfully re-assigned to employee.';
						$result['code'] = 0;
					}
				} else {
					$result['result'] = 'Please select paylabels and employee to assign.';
					$result['code'] = 2;
				}
			// } else {
				// $result['result'] = 'Something went wrong, please try again.';
				// $result['code'] = 1;
			// }

			return response()->json($result);
		}
	}

	public function assignedPayhead(Request $request) {
		if (request()->ajax()) {
			$result = array();
			$requestData =$request->all();

			$emp_code = $requestData['emp_code'];
			
			$assignPayheadsids = Paystructure::select('payhead_id')->
				join('payheads', function($join) {
	            	$join->on('payheads.id', '=', 'paystructures.payhead_id');
	           	})
	           	->where('user_id', $emp_code)->pluck('payhead_id');

			$result = Paystructure::
				join('payheads', function($join) {
	            	$join->on('payheads.id', '=', 'paystructures.payhead_id');
	           	})
	           	->where('user_id', $emp_code)->get();

	        $query = Payhead::select('*');
	        if (count($assignPayheadsids) > 0) {
	        	$query->whereNotIn('payheads.id', $assignPayheadsids);
	        }

	        $payheads = $query->where('payheads.created_by', auth()->user()->id)->get();

			return response()->json([
				'result' => $result,
				'code' => 0,
				'payheads' => $payheads
			]);
		}
	}
}