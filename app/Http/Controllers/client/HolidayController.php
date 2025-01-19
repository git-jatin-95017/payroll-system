<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
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
		if (request()->ajax()) {
			$holidayQuery = Holiday::query();

			$data = $holidayQuery->select('*')->where('holidays.created_by', auth()->user()->id)  ->get();
			
			return Datatables::of($data)
					->addIndexColumn()   					            
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
					})
					->addColumn('holiday_date', function ($row) {
						return date('m-d', strtotime($row->holiday_date));
					})
					->addColumn('holiday_type', function ($row) {

						$type = NULL;
						if ($row->type == 1) {
							$type = 'Public Holiday';
						} elseif ($row->type == 2) {
							$type = 'National Day';
						} elseif ($row->type == 3) {
							$type = 'Voluntary';
						}

						return $type;
					})
					->addColumn('action_button', function($data){
							$btn = "<div class='table-actions'>                           
							<a href='".route("holidays.edit",$data->id)."' class='btn btn-sm btn-primary'><i class='fas fa-pen'></i></a>
							<a data-href='".route("holidays.destroy",$data->id)."' class='btn btn-sm btn-danger delete' style='color:#fff;'><i class='fas fa-trash'></i></a>
							</div>";
							return $btn;
					})
					->rawColumns(['action', 'action_button', 'holiday_type'])
					->make(true);
		}

		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$holidays = Holiday::orderBy('holidays.id', 'desc')
			->where('holidays.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query
					->where(function ($query) use ($searchValue) {
						$query->where('holidays.title', 'like', '%' . $searchValue . '%')
							->orWhere('holidays.description', 'like', '%' . $searchValue . '%')
							->orWhereRaw("DATE_FORMAT(holidays.holiday_date, '%Y-%m-%d') LIKE ?", ['%' . $searchValue . '%'])
							->orWhereRaw("
								CASE 
									WHEN holidays.type = 1 THEN 'Public Holiday'
									WHEN holidays.type = 2 THEN 'National Day'
									WHEN holidays.type = 3 THEN 'Voluntary'
								END LIKE ?", ['%' . $searchValue . '%']);
					});

			})
			->paginate(10);

		return view('holidays.index', compact('holidays'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('holidays.create');
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