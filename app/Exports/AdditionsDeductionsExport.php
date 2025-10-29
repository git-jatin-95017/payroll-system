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
    protected $payLabelFilter;

    public function __construct($earnings, $payLabelFilter = null)
    {
        $this->earnings = $earnings;
        $this->payLabelFilter = $payLabelFilter;
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
            // Filter by pay_label if provided
            if ($this->payLabelFilter && $additional->payhead_id != $this->payLabelFilter) {
                continue;
            }
            $data[] = [
                $earning->user->name,
                date('M d, Y', strtotime($earning->start_date)) . ' - ' . date('M d, Y', strtotime($earning->end_date)),
                $additional->payhead->pay_label,
                $additional->payhead->pay_type == 'nothing' ? 'Addition' : 'Deduction',
                $additional->amount
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