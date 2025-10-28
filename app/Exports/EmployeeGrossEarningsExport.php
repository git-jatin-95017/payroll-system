<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use App\Models\Setting;
use App\Traits\PayrollCalculationTrait;

class EmployeeGrossEarningsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use PayrollCalculationTrait;
    
    protected $earnings;
    protected $settings;

    public function __construct($earnings)
    {
        $this->earnings = $earnings;
        $this->settings = Setting::find(1);
    }

    public function collection()
    {
        return $this->earnings;
    }

    public function headings(): array
    {
        return [
            'Employee',
            'Pay Period',
            'Pay Type',
            'Rate',
            'Hours/Period',
            'Amount'
        ];
    }

    public function map($earning): array
    {
        // Use trait to calculate gross for consistency with confirmation page
        $amounts = $this->calculatePayrollAmounts($earning, $this->settings);
        $gross = $amounts['gross'];
        
        return [
            $earning->user->name ?? '',
            (isset($earning->start_date) && isset($earning->end_date)) ? Carbon::createFromFormat('Y-m-d', $earning->start_date)->format('M d, Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $earning->end_date)->format('M d, Y') : '',
            $earning->user->employeeProfile->pay_type ?? '-',
            number_format($earning->user->employeeProfile->pay_rate, 2, '.', ''),
            number_format($earning->total_hours, 2, '.', ''),
            number_format($gross, 2, '.', '')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:F1' => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'CCCCCC']]],
        ];
    }
} 