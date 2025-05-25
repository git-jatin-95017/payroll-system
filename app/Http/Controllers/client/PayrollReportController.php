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
        
        $totalGrossPay = 0;
        $totalNetPay = 0;
        $totalPaidTimeOff = 0;
        $totalEmployees = $payrolls->unique('user_id')->count();

        foreach ($payrolls as $payroll) {
            $amounts = $this->calculatePayrollAmounts($payroll, $settings);
            $totalGrossPay += $amounts['gross'];
            $totalNetPay += $amounts['net_pay'];
            $totalPaidTimeOff += $payroll->paid_time_off;
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
} 