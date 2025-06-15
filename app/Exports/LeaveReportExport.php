<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeaveReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $leaveRequests;

    public function __construct($leaveRequests)
    {
        $this->leaveRequests = $leaveRequests;
    }

    public function collection()
    {
        return $this->leaveRequests;
    }

    public function headings(): array
    {
        return [
            'Employee',
            'Pay Period',
            'Requests',
            'Status',
            'Paid/Unpaid',
            'Leave Policy',
            'Total Used',
            'Total Remaining'
        ];
    }

    public function map($request): array
    {
        return [
            $request->user->name ?? '',
            $request->pay_period ?? '',
            $request->requests ?? '',
            ucfirst($request->leave_status ?? ''),
            $request->leave_type ?? '',
            $request->leaveType->name ?? '',
            $request->total_used ?? '',
            $request->leave_balance ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:H1' => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'CCCCCC']]],
        ];
    }
} 