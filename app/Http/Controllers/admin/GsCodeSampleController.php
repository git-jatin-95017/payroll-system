<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\GsCodeSamplesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\GsCodeSample;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class GsCodeSampleController extends Controller
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
            $data = GsCodeSample::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<button data-remote="/admin/gs-code/'.$row->id.'" class="btn btn-sm btn-danger btn-delete">Delete</button >';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $html = $builder->columns([
                ['data' => 'action', 'footer' => 'Action'],
                ['data' => 'id', 'footer' => 'Id'],
                ['data' => 'item_codes', 'footer' => 'Item Codes'],
                ['data' => 'master_item_codes', 'footer' => 'Master Item Codes'],
                ['data' => 'final_item', 'footer' => 'Final Item'],
                ['data' => 'component_items', 'footer' => 'Component Items'],
                ['data' => 'category', 'footer' => 'Category'],
                ['data' => 'store_type', 'footer' => 'Store Type'],
                ['data' => 'details', 'footer' => 'Details'],                
                ['data' => 'standard_amounts', 'footer' => 'Standard Amounts'],  
                ['data' => 'standard_units', 'footer' => 'Standard Units'],  
                ['data' => 'unit_type', 'footer' => 'Unit Type'],  
                ['data' => 'created_at', 'footer' => 'Created At'],
                ['data' => 'updated_at', 'footer' => 'Updated At'],
            ])->parameters([
                // 'responsive' => true,
                'autoWidth' => true,
                'scrollX' =>true,
                "scrollY"=> "400px",
            ]);

        return view('admin.gs-code.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.gs-code.create');
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

        Excel::import(new GsCodeSamplesImport, $request->file('file'));
 
        return redirect('admin/gs-code')->with('status', 'The excel file has been imported successfully to database.');
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
        $code = GsCodeSample::find($id);
        $code->delete();

        return true;
    }
}
