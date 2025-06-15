<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdditionsDeductionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'Pay Label',
            'Addition/Deduction',
            'Amount'
        ];
    }

    public function map($earning): array
    {
        $data = [];
        foreach ($earning->additionalEarnings as $additional) {
            $data[] = [
                $earning->user->name,
                date('M d, Y', strtotime($earning->start_date)) . ' - ' . date('M d, Y', strtotime($earning->end_date)),
                $additional->payhead->pay_label,
                $additional->payhead->pay_type == 'nothing' ? 'Addition' : 'Deduction',
                number_format($additional->amount, 2)
            ];
        }
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:E1' => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'CCCCCC']]],
        ];
    }
} 