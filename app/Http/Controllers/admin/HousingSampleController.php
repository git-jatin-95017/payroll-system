<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\HousingSamplesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HousingSample;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class HousingSampleController extends Controller
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
            $data = HousingSample::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<button data-remote="/admin/housing/'.$row->id.'" class="btn btn-sm btn-danger btn-delete">Delete</button >';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $html = $builder->columns([
                ['data' => 'action', 'footer' => 'Action'],
                ['data' => 'id', 'footer' => 'Id'],
                ['data' => 'location_codes', 'footer' => 'Location Codes'],
                ['data' => 'source', 'footer' => 'Source'],
                ['data' => 'url', 'footer' => 'Url'],
                ['data' => 'price_type', 'footer' => 'Price Type'],
                ['data' => 'house_type', 'footer' => 'House Type'],
                ['data' => 'price', 'footer' => 'Price'],
                ['data' => 'currency', 'footer' => 'Currency'],
                ['data' => 'bedrooms', 'footer' => 'Bedrooms'],
                ['data' => 'bathrooms', 'footer' => 'Bathrooms'],
                ['data' => 'size', 'footer' => 'Size'],
                ['data' => 'size_units', 'footer' => 'Size Units'],
                ['data' => 'address', 'footer' => 'Address'],
                ['data' => 'housing_codes', 'footer' => 'Housing Codes'],                        
                ['data' => 'created_at', 'footer' => 'Created At'],
                ['data' => 'updated_at', 'footer' => 'Updated At'],
            ])->parameters([
                // 'responsive' => true,
                'autoWidth' => true,
                'scrollX' =>true,
                "scrollY"=> "400px",
            ]);

        return view('admin.housing.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.housing.create');
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

        Excel::import(new HousingSamplesImport, $request->file('file'));
 
        return redirect('admin/housing')->with('status', 'The excel file has been imported successfully to database.');
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
        $code = HousingSample::find($id);
        $code->delete();

        return true;
    }
}
