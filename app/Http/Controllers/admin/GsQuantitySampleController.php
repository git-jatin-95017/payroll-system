<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\GsQuantitySamplesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\GsQuantitySample;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class GsQuantitySampleController extends Controller
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
            $data = GsQuantitySample::select('*');
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
                ['data' => 'quantities', 'footer' => 'quantities'],                              
                ['data' => 'created_at', 'footer' => 'Created At'],
                ['data' => 'updated_at', 'footer' => 'Updated At'],
            ])->parameters([
                // 'responsive' => true,
                'autoWidth' => true,
                'scrollX' =>true,
                "scrollY"=> "400px",
            ]);

        return view('admin.gs-quantity.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.gs-quantity.create');
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

        Excel::import(new GsQuantitySamplesImport, $request->file('file'));
 
        return redirect('admin/gs-quantity')->with('status', 'The excel file has been imported successfully to database.');
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
