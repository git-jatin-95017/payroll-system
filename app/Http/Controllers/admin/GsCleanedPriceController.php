<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\GsCleanedPrice;
use DataTables;
use Yajra\DataTables\Html\Builder;


class GsCleanedPriceController extends Controller
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
            $data = GsCleanedPrice::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<button data-remote="/admin/gs-cleaned-prices/'.$row->id.'" class="btn btn-sm btn-danger btn-delete">Delete</button >';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $html = $builder->columns([
                ['data' => 'action', 'footer' => 'Action'],
                ['data' => 'id', 'footer' => 'Id'],
                ['data' => 'location_codes', 'footer' => 'Location Codes'],
                ['data' => 'item_codes', 'footer' => 'Item Codes'],                        
                ['data' => 'zip_codes', 'footer' => 'Zip Code'],
                ['data' => 'price', 'footer' => 'Price'],
                ['data' => 'currency_code', 'footer' => 'Currency Code'],
                ['data' => 'amount', 'footer' => 'Amount'],
                ['data' => 'units', 'footer' => 'Units'],
                ['data' => 'website', 'footer' => 'Website'],               
                ['data' => 'store', 'footer' => 'Store'],               
                ['data' => 'store_address', 'footer' => 'Store Address']       
            ])->parameters([
                // 'responsive' => true,
                // 'autoWidth' => true,
                // 'scrollX' =>true,
                // "scrollY"=> "400px",
            ]);

        return view('admin.gs-cleaned-prices.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {        
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
        $code = GsCleanedPrice::find($id);
        $code->delete();

        return true;
    }
}
