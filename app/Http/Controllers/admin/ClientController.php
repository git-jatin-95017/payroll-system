<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
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
		if (request()->ajax()) {
			$usersQuery = User::query();
 
			$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
			$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
	 
			if ($start_date && $end_date) {
	 
				$start_date = date('Y-m-d', strtotime($start_date));
				$end_date = date('Y-m-d', strtotime($end_date));	
				$usersQuery->whereRaw("date(created_at) >= '" . $start_date . "' AND date(created_at) <= '" . $end_date . "'");
			}

			$data = $usersQuery->select('*')->where('role_id', 2);
			
			return Datatables::of($data)
					->addIndexColumn()                   
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
					})
					->addColumn('action_button', function($data){
                            $btn = "<div class='table-actions'>                           
                            <a href='".route("client.edit",$data->id)."'><i class='fas fa-pen'></i></a>
                            <a data-href='".route("client.destroy",$data->id)."' class='delete cursure-pointer'><i class='fas fa-trash'></i></a>
                            </div>";
                            return $btn;
                    })
					->rawColumns(['action', 'action_button'])
					->make(true);
		}

		return view('admin.client.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('admin.client.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) 
	{
		$data = $request->all();

		$request->validate([
			'name' => 'required|max:255',
			'email' => ['required', 'email', 'max:191', 'unique:users'],
			'phone_number' => ['required'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => 'required_with:password'
		]);

		User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'phone_number' => $data['phone_number'],
			'password' => Hash::make($data['password']),
			'role_id' => 2,
			'user_code' => $this->generateUniqueNumber()
		]);

		return redirect()->route('client.index')->with('message', 'Client created successfully.');
	}

	/**
     * Write code on Method
     *
     * @return response()
     */
    protected function generateUniqueNumber()
    {
        do {
            $code = random_int(100000, 999999);
        } while (User::where("user_code", "=", $code)->first());
  
        return $code;
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$client = User::findOrFail($id);		

		return view('admin.client.edit', compact('client'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$id],
			'phone_number' => ['required']                  
		]);

		$client = User::findOrFail($id);

		if (!empty($request->password)) {
			$request['password'] =  Hash::make($request->password);
		} else {
			unset($request['password']);
		}

		$client->update($request->all());

		return redirect()->route('client.index')->with('message','Record updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$code = User::find($id);
		$code->delete();

		return true;
	}

	public function deleteAll(Request $request)  
	{  
		if (request()->ajax()) {

			if (request()->is_delete_request) {

				User::whereIn('id', $request->get('ids'))->delete();

				return response()->json(['status'=>true,'message'=>"Records deleted successfully."]);
			}

			if (request()->is_delete_request_all) {

				User::delete();

				return response()->json(['status'=>true,'message'=>"All Records deleted successfully."]);
			}
		} 
	} 
}
