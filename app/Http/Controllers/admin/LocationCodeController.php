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
			$data = LocationCode::select('*');
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
		//
	}
}
