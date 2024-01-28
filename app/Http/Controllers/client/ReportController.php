<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSheet;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveType;
use App\Models\PayrollAmount;
use App\Models\AdditionalEarning;
use App\Models\AdditionalPaid;
use App\Models\AdditionalUnPaid;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentDetail;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReportController extends Controller
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

    public function index(Request $request) {
        $results = PayrollSheet::where('approval_status', 1)
            ->orderBy('appoval_number')
            ->whereNotNull('date_range')
            ->get()
            ->groupBy(function($item) {
                return $item->appoval_number;
            }
        );

        return view('client.report.index', compact('results'));
    }

    public function showMedicalForm(Request $request) {
        $payrolls = $request['payroll'];

        return view('client.report.medical', compact('payrolls'));
    }
}
