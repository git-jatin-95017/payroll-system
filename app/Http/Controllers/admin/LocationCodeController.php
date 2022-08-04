<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\LocationCodesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LocationCode;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class LocationCodeController extends Controller
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

			$usersQuery = LocationCode::query();
 
	        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
	        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
	 
	        if($start_date && $end_date){
	 
	         $start_date = date('Y-m-d', strtotime($start_date));
	         $end_date = date('Y-m-d', strtotime($end_date));
	 
	         $usersQuery->whereRaw("date(location_codes.created_at) >= '" . $start_date . "' AND date(location_codes.created_at) <= '" . $end_date . "'");
	        }
			$data = $usersQuery->select('*');
			return Datatables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($row) {
						return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
		                // return '<button data-remote="/admin/location-codes/'.$row->id.'" class="btn btn-sm btn-danger btn-delete">Delete</button >';
		            })
					->rawColumns(['action'])			
					->make(true);
		}

		$html = $builder->columns([
				['data' => 'action', 'footer' => 'Action'],
				['data' => 'id', 'footer' => 'Id'],
				['data' => 'location_codes', 'footer' => 'Location Codes'],
				['data' => 'city', 'footer' => 'City'],
				['data' => 'province', 'footer' => 'Province'],
				['data' => 'postal_code', 'footer' => 'Postal Code'],
				['data' => 'city_province', 'footer' => 'City Province'],
				['data' => 'country', 'footer' => 'Country'],
				['data' => 'country_code', 'footer' => 'Country Codes'],
				['data' => 'province_code', 'footer' => 'Province Codes'],
				['data' => 'metropolitan_codes', 'footer' => 'Metropolitan Codes'],
				['data' => 'sub_metropolitan_codes', 'footer' => 'Sub Metropolitan Codes'],
				['data' => 'region', 'footer' => 'Region'],
				['data' => 'iso_3166_alpha_2', 'footer' => 'ISO 3166 Alpha 2'],
				['data' => 'iso_3166_alpha_3', 'footer' => 'ISO 3166 Alpha 3'],
				['data' => 'iso_4217_currency_name', 'footer' => 'ISO 4217 Currency Name'],
				['data' => 'iso_4217_alphabetic_Codes', 'footer' => 'ISO 4217 Alphabetic Codes'],
				['data' => 'iso_4217_numeric_Codes', 'footer' => 'ISO 4217 Numeric Codes'],
				['data' => 'tax_codes', 'footer' => 'Tax Codes'],
				['data' => 'created_at', 'footer' => 'Created At'],
				['data' => 'updated_at', 'footer' => 'Updated At'],
			])->parameters([
				// 'responsive' => true,
				'autoWidth' => true,
				'scrollX' =>true,
				"scrollY"=> "400px",
			]);

		return view('admin.location-codes.index', compact('html'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	   return view('admin.location-codes.create');
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

		Excel::import(new LocationCodesImport, $request->file('file'));
 
		return redirect('admin/location-codes')->with('status', 'The excel file has been imported successfully to database.');
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
        $code = LocationCode::find($id);
        $code->delete();

        return true;
    }

    public function deleteAll(Request $request)  
    {  
        if (request()->ajax()) {

			if (request()->is_delete_request) {

		        LocationCode::whereIn('id', $request->get('ids'))->delete();

		        return response()->json(['status'=>true,'message'=>"Records deleted successfully."]);
			}

			if (request()->is_delete_request_all) {

		        LocationCode::truncate();

		        return response()->json(['status'=>true,'message'=>"All Records deleted successfully."]);
			}
		} 
    }  
}
