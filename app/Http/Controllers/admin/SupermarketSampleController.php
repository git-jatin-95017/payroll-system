<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\SupermarketSamplesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SupermarketSample;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class SupermarketSampleController extends Controller
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
			$data = SupermarketSample::select('*');
			return Datatables::of($data)
					->addIndexColumn()
					// ->addColumn('action', function($row){

					//         $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Delete</a>';

					//         return $btn;
					// })
					->rawColumns(['action'])
					->make(true);
		}

		$html = $builder->columns([
				['data' => 'id', 'footer' => 'Id'],
				['data' => 'location_codes', 'footer' => 'location_codes'],
				['data' => 'item_codes', 'footer' => 'item_codes'],
				['data' => 'item_id', 'footer' => 'item_id'],
				['data' => 'postal_code', 'footer' => 'postal_code'],
				['data' => 'url', 'footer' => 'url'],
				['data' => 'name', 'footer' => 'name'],
				['data' => 'price', 'footer' => 'price'],
				['data' => 'currency', 'footer' => 'currency'],
				['data' => 'source', 'footer' => 'source'],
				['data' => 'number_of_units', 'footer' => 'number_of_units'],
				['data' => 'final_units', 'footer' => 'final_units'],
				['data' => 'created_at', 'footer' => 'Created At'],
				['data' => 'updated_at', 'footer' => 'Updated At'],
			])->parameters([
				// 'responsive' => true,
				'autoWidth' => true,
				'scrollX' =>true,
				"scrollY"=> "400px",
			]);

		return view('admin.super-market.index', compact('html'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('admin.super-market.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		ini_set('max_execution_time', '300');
		$validatedData = $request->validate([
		   'file' => 'required'
		]);

		// $headings = (new HeadingRowImport)->toArray( $request->file('file'));

		Excel::import(new SupermarketSamplesImport, $request->file('file'));

		return redirect('admin/super-market')->with('status', 'The excel file has been imported successfully to database.');
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
		//
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
