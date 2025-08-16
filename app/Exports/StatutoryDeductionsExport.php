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
        $total = ($earning->medical * 2) + ($earning->security * 2) + ($earning->edu_levy * 2);
        
        return [
            $earning->user->name,
            date('M d, Y', strtotime($earning->start_date)) . ' - ' . date('M d, Y', strtotime($earning->end_date)),
            $earning->medical,
            $earning->security,
            $earning->edu_levy,
            $earning->medical,
            $earning->security,
            $earning->edu_levy,
            $total
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