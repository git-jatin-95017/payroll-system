<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\HousingCodesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HousingCode;
use Maatwebsite\Excel\HeadingRowImport;
use DataTables;
use Yajra\DataTables\Html\Builder;

class HousingCodeController extends Controller
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
            $usersQuery = HousingCode::query();
 
            $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
            $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
     
            if($start_date && $end_date){
     
             $start_date = date('Y-m-d', strtotime($start_date));
             $end_date = date('Y-m-d', strtotime($end_date));
     
             $usersQuery->whereRaw("date(created_at) >= '" . $start_date . "' AND date(created_at) <= '" . $end_date . "'");
            }
            $data = $usersQuery->select('*');
            // $data = HousingCode::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<input type="checkbox" class="delete_check" id="delcheck_'.$row->id.'" onclick="checkcheckbox();" value="'.$row->id.'">';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $html = $builder->columns([
                ['data' => 'action', 'footer' => 'Action'],
                ['data' => 'id', 'footer' => 'Id'],
                ['data' => 'item_codes', 'footer' => 'Item Codes'],
                ['data' => 'price_type', 'footer' => 'Price Type'],
                ['data' => 'housing_type', 'footer' => 'Housing Type'],
                ['data' => 'bedroom_size', 'footer' => 'Bedroom Size']
            ])->parameters([
                // 'responsive' => true,
                // 'autoWidth' => true,
                // 'scrollX' =>true,
                "scrollY"=> "400px",
            ]);

        return view('admin.housing-code.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.housing-code.create');
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

        Excel::import(new HousingCodesImport, $request->file('file'));
 
        return redirect('admin/housing-code')->with('status', 'The excel file has been imported successfully to database.');
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
        $code = HousingCode::find($id);
        $code->delete();

        return true;
    }

    public function deleteAll(Request $request)  
    {  
        if (request()->ajax()) {

            if (request()->is_delete_request) {

                HousingCode::whereIn('id', $request->get('ids'))->delete();

                return response()->json(['status'=>true,'message'=>"Records deleted successfully."]);
            }

            if (request()->is_delete_request_all) {

                HousingCode::truncate();

                return response()->json(['status'=>true,'message'=>"All Records deleted successfully."]);
            }
        } 
    }
}
