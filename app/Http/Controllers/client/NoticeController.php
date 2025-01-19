<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NoticeController extends Controller
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
			$holidayQuery = Notice::query();

			$data = $holidayQuery->select('*')->where('notices.created_by', auth()->user()->id)->get();
			
			return Datatables::of($data)
					->addIndexColumn()   					            
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
					})
					->addColumn('action_button', function($data) {
							$btn = "<div class='table-actions'>                           
							<a href='".route("notice.edit",$data->id)."' class='btn btn-sm btn-primary'>Edit</a>
							<a data-href='".route("notice.destroy",$data->id)."' class='btn btn-sm btn-danger delete' style='color:#fff;'>Delete</a>
							</div>";
							return $btn;
					})
					->rawColumns(['action', 'action_button'])
					->make(true);
		}

		// Search input
		$searchValue = $request->input('search', '');

		// Fetching data with search and pagination
		$notices = Notice::orderBy('notices.id', 'desc')
			->where('notices.created_by', auth()->user()->id)
			->where(function ($query) use ($searchValue) {
				$query
					->where(function ($query) use ($searchValue) {
						$query->where('notices.message', 'like', '%' . $searchValue . '%')
							->orWhereRaw("DATE_FORMAT(notices.created_at, '%Y-%m-%d') LIKE ?", ['%' . $searchValue . '%']);
					});

			})
			->paginate(10);

		return view('notice.index', compact('notices'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('notice.create');
	}

	public function store(Request $request)
	{   
		$data = $request->all();

		$request->validate([
			'message' => ['required', 'max:500'],
		]);

		Notice::create([
			'message' => $data['message'],
		]);		

		
		return redirect()->route('notice.index')->with('message', 'Notice created successfully.');	
	}

	public function show(Notice $notice) {   
		return view('notice.show', compact('notice'));
	}

	public function edit(Notice $notice)
	{
	   return view('notice.edit', compact('notice'));
	}

	public function update(Request $request, Notice $notice)
	{
		$data = $request->all();

		$request->validate([
			'message' => ['required', 'max:500']
		]);

		$notice->update([
			'message' => $data['message'],
		]);		
	
		return redirect()->route('notice.index')->with('message', 'Notice updated successfully.');	
	}   

	protected function permanentDelete($id){
        $trash = Notice::find($id);

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

			return response()->json(['status'=>true, 'message'=>"Notice deleted successfully."]);
		}
	}

    // Fetch notices
    public function fetchNotices()
    {
        $notices = DB::table('notices')
            ->orderBy('created_at', 'desc')
			->where('notices.created_by', auth()->user()->id)
            ->limit(3)
            ->get();

        return response()->json($notices);
    }
}
