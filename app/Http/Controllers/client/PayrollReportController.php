<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollAmount;
use App\Models\User;
use App\Models\Department;
use App\Models\Setting;
use App\Traits\PayrollCalculationTrait;
use Carbon\Carbon;
use PDF;
use App\Exports\PayrollExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Models\Attendance;
use App\Models\Checkin;
use App\Models\Payroll;
use App\Exports\EmployeeGrossEarningsExport;
use App\Exports\StatutoryDeductionsExport;
use App\Models\Payhead;
use App\Exports\AdditionsDeductionsExport;
use App\Models\LeaveType;
use App\Exports\LeaveReportExport;
use App\Models\Leave;
use App\Models\AssignLeave;
use App\Exports\EmployerPaymentsExport;

class PayrollReportController extends Controller
{
    use PayrollCalculationTrait;

    public function summary(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.departments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('pay_type')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('pay_type', $request->pay_type);
            });
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payrolls = $query->get();
        $settings = Setting::first();
        
        $totalPayroll = 0;
        $totalTaxes = 0;
        $directDeposits = 0;
        $cheques = 0;

        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            $totalPayroll += $amounts['total_payroll'];
            $totalTaxes += $amounts['medical_benefits'] + $amounts['social_security'] + $amounts['education_levy'] + $amounts['social_security_employer'];
            
            if ($payroll->payment_method === 'direct_deposit') {
                $directDeposits++;
            } else {
                $cheques++;
            }
        }

        $departments = Department::all();

        return view('client.payroll.reports.summary', compact('payrolls', 'departments', 'totalPayroll', 'totalTaxes', 'directDeposits', 'cheques'));
    }

    public function taxes(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }
        if ($request->filled('tax_type')) {
            switch ($request->tax_type) {
                case 'medical':
                    $query->where('medical', '>', 0);
                    break;
                case 'social_security':
                    $query->where('security', '>', 0);
                    break;
                case 'education_levy':
                    $query->where('edu_levy', '>', 0);
                    break;
            }
        }
        if ($request->filled('age_group')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $dob = Carbon::parse($q->dob);
                $age = $dob->age;
                switch ($request->age_group) {
                    case 'under_60':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= 60');
                        break;
                    case '60_to_70':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 60 AND TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= 70');
                        break;
                    case 'over_70':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 70');
                        break;
                }
            });
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', $request->department_id);
            });
        }

        $payrolls = $query->get();
        $settings = Setting::first();
        
        $totalMedicalBenefits = 0;
        $totalSocialSecurity = 0;
        $totalEducationLevy = 0;
        $totalSocialSecurityEmployer = 0;

        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            $totalMedicalBenefits += $amounts['medical_benefits'];
            $totalSocialSecurity += $amounts['social_security'];
            $totalEducationLevy += $amounts['education_levy'];
            $totalSocialSecurityEmployer += $amounts['social_security_employer'];
        }

        $departments = Department::all();

        return view('client.payroll.reports.taxes', compact(
            'payrolls', 
            'departments', 
            'totalMedicalBenefits', 
            'totalSocialSecurity', 
            'totalEducationLevy', 
            'totalSocialSecurityEmployer'
        ));
    }

    private function getDefaultDateRange()
    {
        return [
            'start_date' => Carbon::now()->subWeek()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ];
    }

    public function employeeEarnings(Request $request)
    {
		DB::enableQueryLog();
		$query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);

        // Get default dates if not provided
        $defaultDates = $this->getDefaultDateRange();
        
        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        } else {
            $query->whereDate('start_date', '>=', $defaultDates['start_date']);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        } else {
            $query->whereDate('end_date', '<=', $defaultDates['end_date']);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $payrolls = $query->where('payroll_amounts.created_by', auth()->user()->id)->where('status', 1)->get();
		/*$query = DB::getQueryLog($payrolls);
$query = end($query);
dd($query);*/
        $settings = Setting::first();
        
        $totalGrossPay = 0;
        $totalNetPay = 0;
        $totalPaidTimeOff = 0;
        $totalEmployees = $payrolls->unique('user_id')->count();
        $i = 0;
        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            $totalGrossPay += $amounts['gross'];
            $totalNetPay += $amounts['net_pay'];
            $totalPaidTimeOff += $payroll->paid_time_off;
			$payrolls[$i]['employee_pay'] = $amounts['employee_pay'];
			$i++;
        }

        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.employee-earnings', compact(
            'payrolls', 
            'departments', 
            'employees',
            'totalGrossPay',
            'totalNetPay',
            'totalPaidTimeOff',
            'totalEmployees'
        ));
    }

    public function employerPayments(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);

        // Get default dates if not provided
        $defaultDates = $this->getDefaultDateRange();
        
        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        } else {
            $query->whereDate('start_date', '>=', $defaultDates['start_date']);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        } else {
            $query->whereDate('end_date', '<=', $defaultDates['end_date']);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $payrolls = $query->where('payroll_amounts.created_by', auth()->user()->id)->where('status', 1)->get();
        $settings = Setting::first();
        
        $totalEmployerPayments = 0;
        $totalMedicalBenefits = 0;
        $totalSocialSecurityEmployer = 0;

        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            $totalEmployerPayments += $amounts['total_payroll'];
            $totalMedicalBenefits += $amounts['medical_benefits'];
            $totalSocialSecurityEmployer += $amounts['social_security_employer'];
        }

        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.employer-payments', compact(
            'payrolls', 
            'departments', 
            'employees',
            'totalEmployerPayments',
            'totalMedicalBenefits',
            'totalSocialSecurityEmployer'
        ));
    }

    public function downloadReportPdf($type)
    {
	   $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);
        
        // Apply the same filters as in the view
        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse(request('start_date')));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse(request('end_date')));
        }
        if (request()->filled('department_id')) {
            $query->whereHas('user.departments', function($q) {
                $q->where('department_id', request('department_id'));
            });
        }
        if (request()->filled('employee_id')) {
            $query->where('user_id', request('employee_id'));
        }

        $payrolls = $query->where('payroll_amounts.created_by', auth()->user()->id)->where('status', 1)->get();
		
        $settings = Setting::first();
        
        $totalGrossPay = 0;
        $totalNetPay = 0;
        $totalPaidTimeOff = 0;
        $totalEmployees = $payrolls->unique('user_id')->count();
		$totalemppay = 0;
		$i=0;
        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
			
            $totalGrossPay += $amounts['gross'];
            $totalNetPay += $amounts['net_pay'];
            $totalPaidTimeOff += $payroll->paid_time_off;
			$payrolls[$i]['employee_pay'] = $amounts['employee_pay'];
			$i++;
        }

        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();
        
        $pdf = PDF::loadView('client.payroll.reports.pdf.' . $type, compact('payrolls', 
            'departments', 
            'employees',
            'totalGrossPay',
            'totalNetPay',
            'totalPaidTimeOff',
            'totalEmployees'));
        return $pdf->download($type . '-report.pdf');
    }
	
	
	public function view(): View
    {
        return view('exports.invoices', [
            'invoices' => Invoice::all()
        ]);
    }
	public function downloadReportExcel($type)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);
        
        // Apply the same filters as in the view
        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse(request('start_date')));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse(request('end_date')));
        }
        if (request()->filled('department_id')) {
            $query->whereHas('user.departments', function($q) {
                $q->where('department_id', request('department_id'));
            });
        }
        if (request()->filled('employee_id')) {
            $query->where('user_id', request('employee_id'));
        }

        $payrolls = $query->where('payroll_amounts.created_by', auth()->user()->id)->where('status', 1)->get();
        
        if ($type === 'employer-payments') {
            $data = [];
            foreach ($payrolls as $payroll) {
                $gross = $payroll->gross + $payroll->paid_time_off;
                $mbse_deductions = $payroll->medical + $payroll->security + $payroll->edu_levy;
                $nothingAdditionTonetPay = $payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount');
                $deductions = $payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount');
                $employeePay = $gross - $mbse_deductions + $nothingAdditionTonetPay - $deductions;
                $employeeTaxes = $payroll->medical + $payroll->security + $payroll->edu_levy;
                $employerTaxes = $payroll->medical + $payroll->security_employer;
                $subtotal = $employeePay + $employeeTaxes + $employerTaxes;

                $data[] = [
                    'employee' => $payroll->user->name,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'employee_pay' => number_format($employeePay, 2),
                    'employee_taxes' => number_format($employeeTaxes, 2),
                    'employer_taxes' => number_format($employerTaxes, 2),
                    'subtotal' => number_format($subtotal, 2)
                ];
            }
            return Excel::download(new EmployerPaymentsExport($data), 'employer-payments-report.xlsx');
        }
        
        $settings = Setting::first();
        
        $totalGrossPay = 0;
        $totalNetPay = 0;
        $totalPaidTimeOff = 0;
        $totalEmployees = $payrolls->unique('user_id')->count();

		
		$data = [];
		$grosspay =0;
		$medicalbenefits =0;
		$socialsecurity =0;
		$educationlevy =0;
		$additions = 0;
		$deductions = 0;
		$totalemppay = 0;
		$i=0;
        foreach ($payrolls as $payroll) {
		    $amounts = $this->calculatePayrollAmounts($payroll, $settings);
			$grosspay += $payroll->gross;
			$medicalbenefits += $payroll->medical;
			$socialsecurity += $payroll->security;
			$educationlevy += $payroll->edu_levy;
			$add =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2);
			$ded =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2);
			$additions += $add;
			$deductions += $ded;
			//$payrolls[$i]['employee_pay'] = $amounts['employee_pay'];
			
			$totalemppay += $amounts['employee_pay']; 
			
			
            $item = [];
			$item['Employee'] = $payroll->user->name;
			$item["Pay Period"] = Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y')  ."-".  Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y');
			$item["Gross Pay"] = number_format($payroll->gross, 2);
			$item["Medical Benefits"] = number_format($payroll->medical, 2);
			$item["Social Security"] = number_format($payroll->security, 2);
			$item["Education Levy"] = number_format($payroll->edu_levy, 2);
			$item["Additions"] = number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2);
			$item["Deductions"] = number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2);
			$item["Employee Pay"] = number_format($amounts['employee_pay'], 2);
            $data[] = $item;
			$i++;
        }
		$item1 = [];
		$item1['Employee'] = "";
		$item1["Pay Period"] = "Total";
		$item1["Gross Pay"] = number_format($grosspay, 2);
		$item1["Medical Benefits"] = number_format($medicalbenefits, 2);
		$item1["Social Security"] = number_format($socialsecurity, 2);
		$item1["Education Levy"] = number_format($educationlevy, 2);
		$item1["Additions"] = number_format($additions, 2);
		$item1["Deductions"] = number_format($deductions, 2);
		$item1["Employee Pay"] = number_format($totalemppay, 2);
		$data[] = $item1;
        
        $headings = ["Employee", "Pay Period", "Gross Pay", "Medical Benefits", "Social Security", "Education Levy", "Additions", "Deductions","Employee Pay"];

        return Excel::download(new PayrollExport($data, $headings),$type . '-report.xlsx');
    }
	
	public function downloadPdf($type)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead']);
        
        // Apply the same filters as in the view
        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse(request('start_date')));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse(request('end_date')));
        }
        if (request()->filled('department_id')) {
            $query->whereHas('user.departments', function($q) {
                $q->where('department_id', request('department_id'));
            });
        }
        if (request()->filled('employee_id')) {
            $query->where('user_id', request('employee_id'));
        }

        $payrolls = $query->where('payroll_amounts.created_by', auth()->user()->id)->where('status', 1)->get();
        
        if ($type === 'employer-payments') {
            $pdf = PDF::loadView('client.payroll.reports.pdf.employer-payments', compact('payrolls'));
            return $pdf->download('employer-payments-report.pdf');
        }
        
        $settings = Setting::first();
        
        // Calculate totals based on report type
        $totals = [];
        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            switch ($type) {
                case 'employee-earnings':
                    $totals['gross_pay'] = ($totals['gross_pay'] ?? 0) + $amounts['gross'];
                    $totals['net_pay'] = ($totals['net_pay'] ?? 0) + $amounts['net_pay'];
                    $totals['paid_time_off'] = ($totals['paid_time_off'] ?? 0) + $payroll->paid_time_off;
                    break;
                case 'employer-payments':
                    $totals['employer_payments'] = ($totals['employer_payments'] ?? 0) + $amounts['total_payroll'];
                    $totals['medical_benefits'] = ($totals['medical_benefits'] ?? 0) + $amounts['medical_benefits'];
                    $totals['social_security_employer'] = ($totals['social_security_employer'] ?? 0) + $amounts['social_security_employer'];
                    break;
            }
        }
        
        $pdf = PDF::loadView('client.payroll.reports.pdf.' . $type, compact('payrolls', 'totals'));
        return $pdf->download($type . '-report.pdf');
    }

    public function attendance(Request $request)
    {
        $query = Checkin::with('user')
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply date filters
        if ($request->filled('start_date')) {
            $query->whereDate('checked_in_at', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('checked_in_at', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', $request->department_id);
            });
        }

        // Apply employee filter
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $attendances = $query->get();
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('created_by', auth()->user()->id)->where('role_id', 3)->get();

        return view('client.payroll.reports.attendance', compact('attendances', 'departments', 'employees'));
    }

    public function downloadAttendanceReportExcel($type)
    {
        $query = Checkin::with('user')
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply date filters
        if (request()->filled('start_date')) {
            $query->whereDate('checked_in_at', '>=', Carbon::parse(request('start_date'))->format('Y-m-d'));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('checked_in_at', '<=', Carbon::parse(request('end_date'))->format('Y-m-d'));
        }

        // Apply department filter
        if (request()->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) {
                $q->where('department', request('department_id'));
            });
        }

        // Apply employee filter
        if (request()->filled('employee_id')) {
            $query->where('user_id', request('employee_id'));
        }

        $attendances = $query->get();
        
        $data = [];
        foreach ($attendances as $attendance) {
            $item = [];
            $item['Employee'] = $attendance->user->name;
            $item['Date'] = date('M d, Y', strtotime($attendance->checked_in_at));
            $item['Check In'] = $attendance->checked_in_at ? date('h:i A', strtotime($attendance->checked_in_at)) : '-';
            $item['Check Out'] = $attendance->checked_out_at ? date('h:i A', strtotime($attendance->checked_out_at)) : '-';
            $item['Duration'] = ($attendance->checked_in_at && $attendance->checked_out_at) ? 
                \Carbon\Carbon::parse($attendance->checked_in_at)->diffInHours(\Carbon\Carbon::parse($attendance->checked_out_at)) . ' hours' : '-';
            $item['Note'] = $attendance->note ?? '-';
            $item['Status'] = 'Completed';
            $data[] = $item;
        }
        
        $headings = ["Employee", "Date", "Check In", "Check Out", "Duration", "Note", "Status"];

        return Excel::download(new PayrollExport($data, $headings), 'attendance-report.xlsx');
    }

    public function downloadAttendanceReportPdf($type)
    {
        $query = Checkin::with('user')
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply date filters
        if (request()->filled('start_date')) {
            $query->whereDate('checked_in_at', '>=', Carbon::parse(request('start_date'))->format('Y-m-d'));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('checked_in_at', '<=', Carbon::parse(request('end_date'))->format('Y-m-d'));
        }

        // Apply department filter
        if (request()->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) {
                $q->where('department', request('department_id'));
            });
        }

        // Apply employee filter
        if (request()->filled('employee_id')) {
            $query->where('user_id', request('employee_id'));
        }

        $attendances = $query->get();
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('created_by', auth()->user()->id)->where('role_id', 3)->get();
        
        $pdf = PDF::loadView('client.payroll.reports.pdf.attendance', compact('attendances', 'departments', 'employees'));
        return $pdf->download('attendance-report.pdf');
    }

    public function employeeGrossEarnings(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.employee-gross-earnings', compact('earnings', 'departments', 'employees'));
    }

    public function downloadEmployeeGrossEarningsExcel(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();

        return Excel::download(new EmployeeGrossEarningsExport($earnings), 'employee-gross-earnings.xlsx');
    }

    public function downloadEmployeeGrossEarningsPdf(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();

        $pdf = PDF::loadView('client.payroll.reports.employee-gross-earnings-pdf', compact('earnings'));
        return $pdf->download('employee-gross-earnings.pdf');
    }

    public function statutoryDeductions(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.statutory-deductions', compact('earnings', 'departments', 'employees'));
    }

    public function downloadStatutoryDeductionsExcel(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();

        return Excel::download(new StatutoryDeductionsExport($earnings), 'statutory-deductions.xlsx');
    }

    public function downloadStatutoryDeductionsPdf(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $earnings = $query->get();

        $pdf = PDF::loadView('client.payroll.reports.pdf.statutory-deductions-pdf', compact('earnings'));
        return $pdf->download('statutory-deductions.pdf');
    }

    public function additionsDeductions(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('pay_label')) {
            $query->whereHas('additionalEarnings.payhead', function($q) use ($request) {
                $q->where('id', $request->pay_label);
            });
        }

        $earnings = $query->get();
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();
        $payLabels = Payhead::where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.additions-deductions', compact('earnings', 'departments', 'employees', 'payLabels'));
    }

    public function downloadAdditionsDeductionsExcel(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('pay_label')) {
            $query->whereHas('additionalEarnings.payhead', function($q) use ($request) {
                $q->where('id', $request->pay_label);
            });
        }

        $earnings = $query->get();

        return Excel::download(new AdditionsDeductionsExport($earnings), 'additions-deductions.xlsx');
    }

    public function downloadAdditionsDeductionsPdf(Request $request)
    {
        $query = PayrollAmount::with(['user.employeeProfile', 'user.departments.department', 'additionalEarnings.payhead'])
            ->where('created_by', auth()->user()->id)
            ->where('status', 1);

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('pay_label')) {
            $query->whereHas('additionalEarnings.payhead', function($q) use ($request) {
                $q->where('id', $request->pay_label);
            });
        }

        $earnings = $query->get();

        $pdf = PDF::loadView('client.payroll.reports.pdf.additions-deductions-pdf', compact('earnings'));
        return $pdf->download('additions-deductions.pdf');
    }

    public function leave(Request $request)
    {
        $query = PayrollAmount::with([
            'user.employeeProfile', 
            'user.departments.department', 
            'additionalPaids.leaveType', 
            'additionalPaids.leaveBalance',
            'additionalUnpaids.leaveType',
            'additionalUnpaids.leaveBalance'
        ])
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('leave_type_id')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('additionalPaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                })
                ->orWhereHas('additionalUnpaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                });
            });
        }

        $payrolls = $query->get();

        $leaveRequests = collect();
        foreach ($payrolls as $payroll) {
            $payPeriodStart = Carbon::parse($request->start_date)->format('Y-m-d'); //$payroll->start_date;
            $payPeriodEnd = Carbon::parse($request->end_date)->format('Y-m-d'); //$payroll->end_date;
            $leaveYear = date('Y', strtotime($payroll->start_date));

            // Paid leaves
            foreach ($payroll->additionalPaids as $paid) {
                $leaveType = $paid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8) ; //+ ($carryOver * 8);

                // Requests: sum of leave_duration from leaves table for this user, type, pay period, approved
                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    // ->where('leave_status', 'approved')
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    // ->where('leave_status', 'approved')
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                // Leave Balance: from leave_balances table for this user, type, and year
                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $paid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Paid',
                    'leaveType' => $paid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }

            // Unpaid leaves
            foreach ($payroll->additionalUnpaids as $unpaid) {
                $leaveType = $unpaid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8); // + ($carryOver * 8);

                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    // ->where('leave_status', 'approved')
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    // ->where('leave_status', 'approved')
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $unpaid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Unpaid',
                    'leaveType' => $unpaid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }
        }
        $departments = Department::where('created_by', auth()->user()->id)->get();
        $employees = User::where('role_id', 3)->where('created_by', auth()->user()->id)->get();
        $leaveTypes = LeaveType::where('created_by', auth()->user()->id)->get();

        return view('client.payroll.reports.leave', compact('leaveRequests', 'departments', 'employees', 'leaveTypes'));
    }

    public function downloadLeaveExcel(Request $request)
    {
        // Build $leaveRequests as in the leave() method
        $query = PayrollAmount::with([
            'user.employeeProfile', 
            'user.departments.department', 
            'additionalPaids.leaveType', 
            'additionalPaids.leaveBalance',
            'additionalUnpaids.leaveType',
            'additionalUnpaids.leaveBalance'
        ])
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('leave_type_id')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('additionalPaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                })
                ->orWhereHas('additionalUnpaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                });
            });
        }

        $payrolls = $query->get();

        $leaveRequests = collect();
        foreach ($payrolls as $payroll) {
            $payPeriodStart = $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d') : $payroll->start_date;
            $payPeriodEnd = $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d') : $payroll->end_date;
            $leaveYear = date('Y', strtotime($payroll->start_date));

            // Paid leaves
            foreach ($payroll->additionalPaids as $paid) {
                $leaveType = $paid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8);

                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $paid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Paid',
                    'leaveType' => $paid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }

            // Unpaid leaves
            foreach ($payroll->additionalUnpaids as $unpaid) {
                $leaveType = $unpaid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8);

                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $unpaid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Unpaid',
                    'leaveType' => $unpaid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }
        }

        return Excel::download(new \App\Exports\LeaveReportExport($leaveRequests), 'leave-report.xlsx');
    }

    public function downloadLeavePdf(Request $request)
    {
        // Build $leaveRequests as in the leave() method
        $query = PayrollAmount::with([
            'user.employeeProfile', 
            'user.departments.department', 
            'additionalPaids.leaveType', 
            'additionalPaids.leaveBalance',
            'additionalUnpaids.leaveType',
            'additionalUnpaids.leaveBalance'
        ])
            ->whereHas('user', function($q) {
                $q->where('created_by', auth()->user()->id);
            });

        // Apply filters if any
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.employeeProfile', function($q) use ($request) {
                $q->where('department', 'LIKE', '%'.$request->department_id.'%');
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('leave_type_id')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('additionalPaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                })
                ->orWhereHas('additionalUnpaids', function($q) use ($request) {
                    $q->where('leave_type_id', $request->leave_type_id);
                });
            });
        }

        $payrolls = $query->get();

        $leaveRequests = collect();
        foreach ($payrolls as $payroll) {
            $payPeriodStart = $request->filled('start_date') ? Carbon::parse($request->start_date)->format('Y-m-d') : $payroll->start_date;
            $payPeriodEnd = $request->filled('end_date') ? Carbon::parse($request->end_date)->format('Y-m-d') : $payroll->end_date;
            $leaveYear = date('Y', strtotime($payroll->start_date));

            // Paid leaves
            foreach ($payroll->additionalPaids as $paid) {
                $leaveType = $paid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8);

                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $paid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $paid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Paid',
                    'leaveType' => $paid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }

            // Unpaid leaves
            foreach ($payroll->additionalUnpaids as $unpaid) {
                $leaveType = $unpaid->leaveType;
                $carryOver = $leaveType->carry_over_amount ?? 0;
                $leaveDays = $leaveType->leave_day ?? 0;
                $total_hours_allowed = ($leaveDays * 8);

                $total_requests = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->count('id');

                $total_used = \App\Models\Leave::where('user_id', $payroll->user_id)
                    ->where('type_id', $unpaid->leave_type_id)
                    ->where(function($q) use ($payPeriodStart, $payPeriodEnd) {
                        $q->whereBetween('start_date', [$payPeriodStart, $payPeriodEnd]);
                    })
                    ->sum('leave_duration');

                $leaveBalance = \App\Models\LeaveBalance::where('user_id', $payroll->user_id)
                    ->where('leave_type_id', $unpaid->leave_type_id)
                    ->where('leave_year', $leaveYear)
                    ->first();

                $leaveRequests->push((object)[
                    'user' => $payroll->user,
                    'pay_period' => Carbon::createFromFormat('Y-m-d', $payroll->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $payroll->end_date)->format('M d, Y'),
                    'requests' => $total_requests,
                    'leave_status' => 'approved',
                    'leave_type' => 'Unpaid',
                    'leaveType' => $unpaid->leaveType,
                    'hours_allowed' => $total_hours_allowed,
                    'total_used' => $total_used,
                    'leave_balance' => $leaveBalance ? $leaveBalance->balance : 0,
                ]);
            }
        }

        $pdf = PDF::loadView('client.payroll.reports.pdf.leave-pdf', compact('leaveRequests'));
        return $pdf->download('leave-report.pdf');
    }
} 