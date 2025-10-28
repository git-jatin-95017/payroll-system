<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatutoryDeductionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $earnings;

    public function __construct($earnings)
    {
        $this->earnings = $earnings;
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
            'Employee Medical Benefits',
            'Employee Social Security',
            'Employee Education Levy',
            'Employer Medical Benefits',
            'Employer Social Security',
            'Employer Education Levy',
            'Total'
        ];
    }

    public function map($earning): array
    {
        // Use pre-calculated values that are already stored on the object
        $medical = $earning->medical ?? 0;
        $security = $earning->security ?? 0;
        $edu_levy = $earning->edu_levy ?? 0;
        $security_employer = $earning->security_employer ?? 0;
        
        // Total = (Employee Medical) + (Employee Security) + (Employee Education Levy) + (Employer Medical) + (Employer Security) + (Employer Education Levy)
        // Since Employer Education Levy is 0, this simplifies to:
        $total = ($medical * 2) + $security + $edu_levy + $security_employer;
        
        return [
            $earning->user->name,
            date('M d, Y', strtotime($earning->start_date)) . ' - ' . date('M d, Y', strtotime($earning->end_date)),
            number_format($medical, 2, '.', ''),    
            number_format($security, 2, '.', ''),
            number_format($edu_levy, 2, '.', ''),
            number_format($medical, 2, '.', ''),
            number_format($security_employer, 2, '.', ''),
            0, // Education levy not applicable for employer
            number_format($total, 2, '.', '')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:I1' => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'CCCCCC']]],
        ];
    }
} 