<?php

namespace App\Traits;

use Carbon\Carbon;

trait PayrollCalculationTrait
{
    protected function calculatePayrollAmounts($row, $settings)
    {
        $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
        $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
        $social_security_amt = $row->social_security ?? $settings->social_security;
        $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
        $education_levy_amt = $row->education_levy ?? $settings->education_levy;
        $education_levy_amt_5 = $row->education_levy_amt_5 > 0 ? $row->education_levy_amt_5 : $settings->education_levy_amt_5;

        $grossT = 0;
        $employeePayT = 0;
        $deductionsT = 0;
        $earningsT = 0;
        $nothingAdditionTonetPayT = 0;

        // Calculate additional earnings and deductions
        if (count($row->additionalEarnings) > 0) {
            foreach($row->additionalEarnings as $val) {
                if($val->payhead->pay_type == 'earnings') {
                    $earningsT += $val->amount;
                }
                if($val->payhead->pay_type == 'deductions') {
                    $deductionsT += $val->amount;
                }
                if($val->payhead->pay_type == 'nothing') {
                    $nothingAdditionTonetPayT += $val->amount;
                }
            }
        }

        $grossT = $row->gross + $row->paid_time_off;

        $pay_type = $row->user->employeeProfile->pay_type;
        $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
        $dob = $diff->format('%y');
        $days = $row->total_hours;

        // Calculate medical benefits based on age
        if ($dob <= 60) {
            $medical_benefitsT = ($grossT * $medical_less_60_amt) / 100;
        } else if ($dob > 60 && $dob <= 79) {
            $medical_benefitsT = ($grossT * $medical_gre_60_amt) / 100;
        } else if ($dob > 70) {
            $medical_benefitsT = 0;
        }

        // Calculate social security and education levy based on pay type
        if ($pay_type == 'hourly' || $pay_type == 'weekly') {
            $social_securityT = ($grossT > 1500 ? ((1500 * $social_security_amt) / 100) : ($grossT * $social_security_amt) / 100);
            $social_securityT_employer = ($grossT > 1500 ? ((1500 * $social_security_employer_amt) / 100) : ($grossT * $social_security_employer_amt) / 100);
            $education_lveyT = ($grossT <= 125 ? 0 : ($grossT > 1154 ? (((1154 - 125) * $education_levy_amt) / 100) + ((($grossT - 1154) * $education_levy_amt_5) / 100) : ((($grossT - 125) * $education_levy_amt) / 100)));
        } else if ($pay_type == 'bi-weekly') {
            if ($days <= 7) {
                $social_securityT = ($grossT > 3000 ? ((3000 * $social_security_amt) / 100) : ($grossT * $social_security_amt) / 100);
                $social_securityT_employer = ($grossT > 3000 ? ((3000 * $social_security_employer_amt) / 100) : ($grossT * $social_security_employer_amt) / 100);
            } else {
                $social_securityT = ($grossT > 3000 ? ((3000 * $social_security_amt) / 100) : ($grossT * $social_security_amt) / 100);
                $social_securityT_employer = ($grossT > 3000 ? ((3000 * $social_security_employer_amt) / 100) : ($grossT * $social_security_employer_amt) / 100);
            }
            $education_lveyT = ($grossT <= 250 ? 0 : ($grossT > 2308 ? (((2308 - 250) * $education_levy_amt) / 100) + ((($grossT - 2308) * $education_levy_amt_5) / 100) : ((($grossT - 250) * $education_levy_amt) / 100)));
        } else if ($pay_type == 'semi-monthly') {
            $social_securityT = ($grossT > 3000 ? ((3000 * $social_security_amt) / 100) : ($grossT * $social_security_amt) / 100);
            $social_securityT_employer = ($grossT > 3000 ? ((3000 * $social_security_employer_amt) / 100) : ($grossT * $social_security_employer_amt) / 100);
            $education_lveyT = ($grossT <= 125 ? 0 : ($grossT > 2500 ? (((2500 - 270.84) * $education_levy_amt) / 100) + ((($grossT - 2500) * $education_levy_amt_5) / 100) : ((($grossT - 270.84) * $education_levy_amt) / 100)));
        } else if ($pay_type == 'monthly') {
            $social_securityT = ($grossT > 6500 ? ((6500 * $social_security_amt) / 100) : ($grossT * $social_security_amt) / 100);
            $social_securityT_employer = ($grossT > 6500 ? ((6500 * $social_security_employer_amt) / 100) : ($grossT * $social_security_employer_amt) / 100);
            $education_lveyT = ($grossT <= 125 ? 0 : ($grossT > 5000 ? (((5000 - 541.67) * $education_levy_amt) / 100) + ((($grossT - 5000) * $education_levy_amt_5) / 100) : ((($grossT - 541.67) * $education_levy_amt) / 100)));
        }

        $mbse_deductions = $medical_benefitsT + $social_securityT + $education_lveyT;
        $net_pay = $grossT - $mbse_deductions;

        // Handle bi-weekly special case
        if ($pay_type == 'bi-weekly' && $days > 7) {
            $net_pay = 2 * $net_pay;
        }

        $employeePayT = $grossT - $mbse_deductions + $nothingAdditionTonetPayT - $deductionsT;
        $totalPayroll = $employeePayT + $mbse_deductions + $row->security_employer;

        return [
            'gross' => $grossT,
            'medical_benefits' => $medical_benefitsT,
            'social_security' => $social_securityT,
            'social_security_employer' => $social_securityT_employer,
            'education_levy' => $education_lveyT,
            'deductions' => $deductionsT,
            'earnings' => $earningsT,
            'nothing_addition' => $nothingAdditionTonetPayT,
            'net_pay' => $net_pay,
            'employee_pay' => $employeePayT,
            'total_payroll' => $totalPayroll
        ];
    }
} 